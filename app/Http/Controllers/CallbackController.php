<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\ContactMessageSent;
use App\Http\Requests\QuickCallbackRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CallbackController extends Controller
{
    /**
     * Handle a 1-field quick phone callback request from the contact modal.
     * Uses existing ContactMessageSent event to deliver notifications to breeder.
     */
    public function store(QuickCallbackRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $rawPhone = strip_tags(trim($validated['phone']));
        $phone = $this->normalizePhoneNumber($rawPhone);

        $catName = ! empty($validated['cat_name']) ? strip_tags(trim($validated['cat_name'])) : null;
        $catBreed = ! empty($validated['cat_breed']) ? strip_tags(trim($validated['cat_breed'])) : null;
        $source = ! empty($validated['traffic_source']) ? strip_tags(trim($validated['traffic_source'])) : 'Bezpośredni / Nieznane';
        $pageUrl = ! empty($validated['page_url'])
            ? strip_tags(trim($validated['page_url']))
            : ($request->headers->get('referer') ?: url()->previous());

        $subject = 'Prośba o szybki kontakt' . ($catName ? ": {$catName}" : '');
        $catInfo = $catName ? "{$catName}" . ($catBreed ? " ({$catBreed})" : '') : 'Ogólne zapytanie o kocięta';
        $submissionTime = now()->format('Y-m-d H:i:s');

        $message = "Prośba o szybki kontakt telefoniczny / WhatsApp z modalnej rezerwacji na stronie.\n\n"
            . "Numer telefonu: {$phone}\n"
            . "Zainteresowanie: {$catInfo}\n"
            . "Podstrona: {$pageUrl}\n"
            . "Źródło ruchu: {$source}\n"
            . "Data zgłoszenia: {$submissionTime}";

        // Concurrency & Race Condition Protection:
        // Prevent duplicate callback notifications within 10 seconds for the same phone & animal inquiry (e.g. rapid double-clicks).
        $dedupKey = 'quick_callback_lock:' . md5($rawPhone . '|' . ($catName ?? 'general'));
        if (! Cache::add($dedupKey, true, 10)) {
            return response()->json([
                'success' => true,
                'message' => 'Dziękujemy! Twoje zgłoszenie zostało już przyjęte. Skontaktujemy się telefonicznie lub przez WhatsApp tak szybko, jak to możliwe.',
            ]);
        }

        try {
            // Dispatch via existing application contact notification event
            ContactMessageSent::dispatch([
                'name'    => 'Szybki kontakt (' . ($catName ?? 'Hodowla') . ')',
                'email'   => null,
                'phone'   => $phone,
                'subject' => $subject,
                'message' => $message,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dziękujemy! Otrzymaliśmy Twój numer. Skontaktujemy się telefonicznie lub przez WhatsApp tak szybko, jak to możliwe.',
            ]);
        } catch (\Throwable $e) {
            // Release deduplication lock on error so user can immediately retry
            Cache::forget($dedupKey);

            // Mask phone for GDPR/privacy compliance in log files (e.g. +48 514 *** *04)
            $maskedPhone = preg_replace('/(\+?\d{2,3}\s*\d{3})\d{3}(\d{2})/', '$1 *** *$2', $phone) ?: '***';

            Log::error('Quick callback submission failed: ' . $e->getMessage(), [
                'exception_class' => get_class($e),
                'cat'             => $catName,
                'phone_mask'      => $maskedPhone,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Wystąpił problem techniczny podczas rejestracji numeru. Prosimy o bezpośredni kontakt telefoniczny pod numerem +48 514 153 204.',
            ], 500);
        }
    }

    /**
     * Normalize Polish and international phone numbers into clean, human-readable format.
     */
    protected function normalizePhoneNumber(string $phone): string
    {
        $trimmed = trim($phone);
        $digitsOnly = preg_replace('/\D/', '', $trimmed);

        // International 00 prefix -> +
        if (str_starts_with($trimmed, '00')) {
            $trimmed = '+' . substr($trimmed, 2);
        }

        // Standard Polish 9-digit format without country code (e.g., 514153204 or 514 153 204)
        if (strlen($digitsOnly) === 9 && ! str_starts_with($trimmed, '+')) {
            return '+48 ' . substr($digitsOnly, 0, 3) . ' ' . substr($digitsOnly, 3, 3) . ' ' . substr($digitsOnly, 6, 3);
        }

        // Polish number with country code +48 and 9 subsequent digits (+48514153204 -> +48 514 153 204)
        if (str_starts_with($trimmed, '+48') && strlen($digitsOnly) === 11) {
            $local = substr($digitsOnly, 2);
            return '+48 ' . substr($local, 0, 3) . ' ' . substr($local, 3, 3) . ' ' . substr($local, 6, 3);
        }

        // Standard clean spaces for international numbers
        return preg_replace('/\s+/', ' ', $trimmed);
    }
}
