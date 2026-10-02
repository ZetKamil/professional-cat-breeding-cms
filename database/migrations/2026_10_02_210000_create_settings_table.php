<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->longText('value')->nullable();
                $table->timestamps();
            });
        }

        // Seed default Global Blog CTA & Cattery Offer
        $defaultCta = [
            'is_enabled'   => true,
            'badge'        => 'Hodowla Kotów z Mazowieckiej Szwajcarii',
            'heading'      => '🐾 Dostępne kocięta brytyjskie i bengalskie w naszej hodowli',
            'body'         => "W naszej hodowli w Sikorzu prowadzimy profesjonalny chów **zarówno kotów bengalskich, jak i brytyjskich**!\n\n"
                . "Aktualnie posiadamy w hodowli:\n"
                . "- 🐱 **Kocięta brytyjskie:** Prześliczne, spokojne pluszowe maluchy o aksamitnym futrze i wspaniałym, łagodnym charakterze — gotowe do rezerwacji i zmiany domu!\n"
                . "- 🐱 **Kocięta bengalskie:** 5 energicznych kociąt z wyrazistym rysunkiem rozet (część gotowa do odbioru od zaraz, część po zakończonej profilaktyce).\n\n"
                . "Zapraszamy do kontaktu — chętnie opowiemy o aktualnych miotach obu ras, pomożemy dobrać charakter kociaka do stylu życia Twojej rodziny oraz zaprosimy na wizytę w hodowli w Sikorzu k. Płocka.\n\n"
                . "👉 **[Zobacz dostępne koty i porozmawiaj z hodowcą →](/koty)**\n\n"
                . "📞 **Telefon / WhatsApp:** +48 514 153 204\n\n"
                . "📍 **Lokalizacja:** Sikórz k. Płocka (woj. mazowieckie — blisko Warszawy, Płocka i Łodzi)",
            'image_url'    => '',
            'button_text'  => 'Zobacz dostępne koty',
            'button_url'   => '/koty',
        ];

        Setting::updateOrCreate(
            ['key' => 'blog_closing_cta'],
            ['value' => json_encode($defaultCta, JSON_UNESCAPED_UNICODE)]
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
