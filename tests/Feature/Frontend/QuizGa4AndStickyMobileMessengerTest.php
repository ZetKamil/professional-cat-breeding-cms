<?php

declare(strict_types=1);

namespace Tests\Feature\Frontend;

use App\Models\Animal;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizGa4AndStickyMobileMessengerTest extends TestCase
{
    use RefreshDatabase;

    public function test_ga4_fires_immediately_on_landing_on_quiz_page(): void
    {
        $response = $this->get('/dobierz-rase?utm_source=fb_ads&utm_medium=cpc&utm_campaign=quiz_kolaz');

        $response->assertOk();

        // 1. GA4 base tag is rendered
        $response->assertSee('googletagmanager.com/gtag/js?id=', false);
        $response->assertSee("gtag('js', new Date());", false);
        $response->assertSee("gtag('config'", false);

        // 2. Consent Mode v2 default setup exists
        $response->assertSee("gtag('consent', 'default'", false);
        $response->assertSee("'analytics_storage': 'denied'", false);

        // 3. Analytics storage is granted immediately on landing unless explicitly declined
        $response->assertSee("gtag('consent', 'update'", false);
        $response->assertSee("'analytics_storage': 'granted'", false);

        // 4. Pageview config preserves full window.location.href (all UTM query params)
        $response->assertSee("'page_location': window.location.href", false);

        // 5. Meta Pixel grant on landing unless explicitly declined
        $response->assertSee("fbq('consent', 'grant')", false);

        // 6. Cookie consent banner has implicit consent auto-dismiss support
        $response->assertSee('window.grantImplicitConsent', false);
    }

    public function test_quiz_interactions_trigger_implicit_consent(): void
    {
        $response = $this->get(route('quiz'));

        $response->assertOk();

        // Check that selectQuizOption invokes grantImplicitConsent
        $response->assertSee('window.grantImplicitConsent();', false);

        // Check that showQuizResult dispatches results-shown event for mobile sticky bar
        $response->assertSee("window.dispatchEvent(new CustomEvent('quiz:results-shown'));", false);
    }

    public function test_luxury_sticky_mobile_messenger_bar_renders_on_eligible_pages(): void
    {
        // 1. Render on /dobierz-rase
        $quizResponse = $this->get(route('quiz'));
        $quizResponse->assertOk();
        $quizResponse->assertSee('id="mobileStickyMessengerBar"', false);
        $quizResponse->assertSee('Pytanie o kociaka lub filmik?');
        $quizResponse->assertSee('💬 Messenger →');
        $quizResponse->assertSee('https://m.me/61580668026948');
        $quizResponse->assertSee('handleStickyMessengerClick', false);

        // 2. Render on /koty (catalog)
        $catalogResponse = $this->get(route('frontend.animals.index'));
        $catalogResponse->assertOk();
        $catalogResponse->assertSee('id="mobileStickyMessengerBar"', false);
        $catalogResponse->assertSee('data-scroll-eligible="1"', false);

        // 3. Render on /koty/{animal} (profile)
        $animal = Animal::factory()->create([
            'name' => 'Bars',
            'slug' => 'bars-bengalski',
            'is_published' => true,
        ]);
        $animalResponse = $this->get(route('frontend.animals.show', $animal));
        $animalResponse->assertOk();
        $animalResponse->assertSee('id="mobileStickyMessengerBar"', false);
        $animalResponse->assertSee('data-scroll-eligible="1"', false);

        // 4. Render on /blog/{post} (single post)
        $post = Post::updateOrCreate(
            ['slug' => 'wszystko-o-kotach-bengalskich'],
            [
                'title' => 'Wszystko o kotach bengalskich',
                'body' => 'Treść artykułu blogowego o rasie kot bengalski.',
                'excerpt' => 'Zajawka artykułu blogowego.',
                'is_published' => true,
                'published_at' => now()->subDay(),
            ]
        );
        $blogResponse = $this->get(route('frontend.blog.show', $post));
        $blogResponse->assertOk();
        $blogResponse->assertSee('id="mobileStickyMessengerBar"', false);
        $blogResponse->assertSee('data-scroll-eligible="1"', false);

        // 5. Must NOT render on non-eligible pages (e.g. /kontakt)
        $contactResponse = $this->get(route('contact'));
        $contactResponse->assertOk();
        $contactResponse->assertDontSee('id="mobileStickyMessengerBar"', false);
    }
}
