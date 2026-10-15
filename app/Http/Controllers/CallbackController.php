<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\ContactMessageSent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CallbackController extends Controller
{
    /**
     * Handle a 1-field quick phone callback request from the reservation modal.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone'          => ['required', 'string', 'min:9', 'max:25'],
            'cat_name'       => ['nullable', 'string', 'max:100'],
            'cat_breed'      => ['nullable', 'string', 'max:100'],
            'traffic_source' => ['nullable', 'string', 'max:100'],
            '_hp_website'    => ['nullable', 'prohibited'],
        ], [
            'phone.required'         => 'Prosimy o podanie numeru telefonu.',
            'phone.min'              => 'Numer telefonu musi mieć co najmniej 9 cyfr.',
            'phone.max'              => 'Numer telefonu jest zbyt długi.',
            '_hp_website.prohibited' => 'Wykryto spam.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $validated = $validator->validated();

        $phone = strip_tags(trim($validated['phone']));
        $catName = ! empty($validated['cat_name']) ? strip_tags(trim($validated['cat_name'])) : null;
        $catBreed = ! empty($validated['cat_breed']) ? strip_tags(trim($validated['cat_breed'])) : null;
        $source = ! empty($validated['traffic_source']) ? strip_tags(trim($validated['traffic_source'])) : 'Bezpośredni / Nieznane';

        $subject = 'Prośba o szybki kontakt' . ($catName ? ": {$catName}" : '');
        $catInfo = $catName ? "{$catName}" . ($catBreed ? " ({$catBreed})" : '') : 'Ogólne zapytanie o kocięta';

        $message = "Prośba o szybki kontakt telefoniczny / WhatsApp z modalnej rezerwacji na stronie.\n\n"
            . "Numer telefonu: {$phone}\n"
            . "Zainteresowanie: {$catInfo}\n"
            . "Źródło ruchu: {$source}\n"
            . "Data zgłoszenia: " . now()->format('Y-m-d H:i:s');

        try {
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
