<?php

declare(strict_types=1);

namespace Tests\Feature\Frontend;

use App\Enums\AnimalGender;
use App\Enums\AnimalStatus;
use App\Enums\AnimalType;
use App\Models\Animal;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogPostLuxuryLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_bengal_blog_post_renders_unified_luxury_cta_and_takeaway_box(): void
    {
        // 1. Get or create Bengal kittens
        $baron = Animal::updateOrCreate(
            ['slug' => 'baron'],
            [
                'name' => 'Baron',
                'breed' => 'Kot Bengalski',
                'status' => AnimalStatus::Available,
                'price' => 2700,
                'is_published' => true,
            ]
        );

        $cyprian = Animal::updateOrCreate(
            ['slug' => 'cyprian'],
            [
                'name' => 'Cyprian',
                'breed' => 'Kot Bengalski',
                'status' => AnimalStatus::Available,
                'price' => 3000,
                'is_published' => true,
            ]
        );

        $carlos = Animal::updateOrCreate(
            ['slug' => 'carlos'],
            [
                'name' => 'Carlos',
                'breed' => 'Kot Bengalski',
                'status' => AnimalStatus::Available,
                'price' => 3000,
                'is_published' => true,
            ]
        );

        // British kitten that should NOT be in the Bengal article's featured strip
        $britishCat = Animal::updateOrCreate(
            ['slug' => 'ada-brytyjka-test'],
            [
                'name' => 'AdaBrytyjka',
                'breed' => 'Kot Brytyjski',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Available,
                'price' => 2500,
                'is_published' => true,
            ]
        );

        // 2. Create post with sections including "Odpowiedź w pigułce" and kitten section
        $post = Post::updateOrCreate(
            ['slug' => 'kot-bengalski-w-domu-mity-zalety-odchowanych-kociat'],
            [
                'title' => 'Kot bengalski w domu – czy naprawdę „chodzi po ścianach”? Prawda o charakterze i zaletach odchowanych kociąt',
                'body' => "Marzysz o kocie bengalskim?\n\n**Odpowiedź w pigułce: Prawidłowo zsocjalizowany kot bengalski nie jest dzikim niszczycielem, lecz niezwykle inteligentnym towarzyszem.**",
                'excerpt' => 'Czy kot bengalski niszczy meble?',
                'is_published' => true,
                'published_at' => now()->subDay(),
                'sections' => [
                    [
                        'heading' => '',
                        'body' => "Marzysz o kocie bengalskim?\n\n**Odpowiedź w pigułce: Prawidłowo zsocjalizowany kot bengalski nie jest dzikim niszczycielem, lecz niezwykle inteligentnym towarzyszem.**",
                        'tip' => '',
                    ],
                    [
                        'heading' => 'Mit nr 1: Kot bengalski zniszczy meble',
                        'body' => 'Większość historii dotyczy kotów bez socjalizacji.',
                        'tip' => 'Wybieraj hodowlę z domową socjalizacją.',
                    ],
                    [
                        'heading' => 'Poznaj nasze odchowane kocięta bengalskie – gotowe do zmiany domu!',
                        'body' => 'W naszej hodowli mamy obecnie 3 wyjątkowe, w pełni odchowane kocięta bengalskie.',
                        'tip' => '',
                    ],
                ],
            ]
        );

        $post->animals()->sync([$baron->id, $cyprian->id, $carlos->id]);

        $response = $this->get(route('frontend.blog.show', $post));

        $response->assertOk();

        // 1. Key takeaway box styled callout
        $response->assertSee('article-takeaway-box', false);
        $response->assertSee('Odpowiedź w pigułce');
        $response->assertSee('Prawidłowo zsocjalizowany kot bengalski nie jest dzikim niszczycielem');

        // 2. Normal H2 section in the article prose (not truncated or trapped in checklist box)
        $response->assertSee('<h2>Poznaj nasze odchowane kocięta bengalskie – gotowe do zmiany domu!</h2>', false);

        // 3. Unified Single Luxury CTA Card (and no duplicate box)
        $response->assertSee('article-luxury-cta-card', false);
        $response->assertSee('🐾 DOMOWA HODOWLA MAZOWIECKA SZWAJCARIA');
        $response->assertSee('Chcesz zobaczyć naszą trójkę odchowanych bengali na żywo lub zapytać o szczegóły?');
        $response->assertSee('Napisz do nas na Messengerze – chętnie prześlemy dzisiejsze filmiki z Baronem, Cyprianem i Carlosem');
        $response->assertSee('💬 Napisz na Messengerze (Zapytaj o kociaki i filmik)');
        $response->assertSee('WhatsApp (+48 514 153 204)');
        $response->assertSee('✨ Nie wiesz, czy Bengal pasuje do Twojego domu? Rozwiąż 45-sekundowy Quiz →');

        // Crucial: No neon blue or green CTA classes, no duplicate article-closing-cta-card
        $response->assertDontSee('article-conversion-cta-box');
        $response->assertDontSee('article-closing-cta-card');
        $response->assertDontSee('#0084FF');
        $response->assertDontSee('#25D366');

        // 4. Featured cats: ONLY the 3 Bengals in a 3-column animals-grid
        $response->assertSee('Koty z tego artykułu');
        $response->assertSee('Baron');
        $response->assertSee('2 700 zł');
        $response->assertSee('Cyprian');
        $response->assertSee('Carlos');
        $response->assertDontSee('AdaBrytyjka');
        $response->assertDontSee('animals-grid--compact');
    }

    public function test_general_blog_post_renders_takeaway_box_and_luxury_card(): void
    {
        $post = Post::updateOrCreate(
            ['slug' => 'ogolny-artykul-o-kotach-test'],
            [
                'title' => 'Jak dbać o zdrowie kota rasowego – poradnik',
                'body' => "Wstęp do artykułu.\n\nOdpowiedź w pigułce: **Zdrowie kota opiera się na regularnych badaniach i diecie wysokomięsnej.**\n\n## Żywienie\n\nOpis diety.",
                'excerpt' => 'Poradnik o zdrowiu kota.',
                'is_published' => true,
                'published_at' => now()->subDay(),
                'sections' => null,
            ]
        );

        $response = $this->get(route('frontend.blog.show', $post));

        $response->assertOk();
        $response->assertSee('article-takeaway-box', false);
        $response->assertSee('Odpowiedź w pigułce');
        $response->assertSee('Zdrowie kota opiera się na regularnych badaniach');
        $response->assertSee('article-luxury-cta-card', false);
        $response->assertSee('🐾 DOMOWA HODOWLA MAZOWIECKA SZWAJCARIA');
        $response->assertSee('💬 Napisz na Messengerze');
        $response->assertSee('WhatsApp (+48 514 153 204)');
    }
}
