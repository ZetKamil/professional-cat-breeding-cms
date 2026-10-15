<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\ContactMessageSent;
use App\Http\Requests\QuickCallbackRequest;
use Illuminate\Http\JsonResponse;
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

        $phone = strip_tags(trim($validated['phone']));
        $catName = ! empty($validated['cat_name']) ? strip_tags(trim($validated['cat_name'])) : null;
        $catBreed = ! empty($validated['cat_breed']) ? strip_tags(trim($validated['cat_breed'])) : null;
        $source = ! empty($validated['traffic_source']) ? strip_tags(trim($validated['traffic_source'])) : 'Bezpośredni / Nieznane';

        $subject = 'Prośba o szybki kontakt' . ($catName ? ": {$catName}" : '');
        $catInfo = $catName ? "{$catName}" . ($catBreed ? " ({$catBreed})" : '') : 'Ogólne zapytanie o kocięta';
        $submissionTime = now()->format('Y-m-d H:i:s');

        $message = "Prośba o szybki kontakt telefoniczny / WhatsApp z modalnej rezerwacji na stronie.\n\n"
            . "Numer telefonu: {$phone}\n"
            . "Zainteresowanie: {$catInfo}\n"
            . "Źródło ruchu: {$source}\n"
            . "Data zgłoszenia: {$submissionTime}";

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
            Log::error('Quick callback submission failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Wystąpił problem techniczny. Zadzwoń bezpośrednio pod numer: +48 514 153 204.',
            ], 500);
        }
    }
}
