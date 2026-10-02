<?php

use App\Models\Post;
use App\Services\AiBlogGeneratorService;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $post = Post::where('slug', 'kot-bengalski-czy-brytyjski')->first();
        if (! $post) {
            return;
        }

        $sections = $post->sections;
        if (empty($sections)) {
            return;
        }

        $lastIdx = count($sections) - 1;

        $britishSectionBody = "W naszej hodowli w Sikorzu prowadzimy profesjonalny chów **zarówno kotów bengalskich, jak i brytyjskich**!\n\n"
            . "Aktualnie posiadamy w hodowli:\n"
            . "- 🐱 **Kocięta brytyjskie:** Prześliczne, spokojne pluszowe maluchy o aksamitnym futrze i wspaniałym, łagodnym charakterze — gotowe do rezerwacji i zmiany domu!\n"
            . "- 🐱 **Kocięta bengalskie:** 5 energicznych kociąt z wyrazistym rysunkiem rozet (część gotowa do odbioru od zaraz, część po zakończonej profilaktyce).\n\n"
            . "Zapraszamy do kontaktu — chętnie opowiemy o aktualnych miotach obu ras, pomożemy dobrać charakter kociaka do stylu życia Twojej rodziny oraz zaprosimy na wizytę w hodowli w Sikorzu k. Płocka.\n\n"
            . "👉 **[Zobacz dostępne koty i porozmawiaj z hodowcą →](/koty)**\n"
            . "📞 **Telefon / WhatsApp:** +48 514 153 204\n"
            . "📍 **Lokalizacja:** Sikórz k. Płocka (woj. mazowieckie — blisko Warszawy, Płocka i Łodzi)";

        $sections[$lastIdx]['heading'] = '🐾 Dostępne kocięta brytyjskie i bengalskie w naszej hodowli';
        $sections[$lastIdx]['body'] = $britishSectionBody;

        $post->sections = $sections;
        $post->body = app(AiBlogGeneratorService::class)->sectionsToBody($sections);
        $post->saveQuietly();
    }

    public function down(): void
    {
    }
};
