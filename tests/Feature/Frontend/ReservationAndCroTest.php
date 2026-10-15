<?php

declare(strict_types=1);

namespace Tests\Feature\Frontend;

use App\Events\ContactMessageSent;
use App\Models\Animal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * Feature tests covering Conversion Rate Optimization (CRO) additions:
 * - 1-field quick callback API with honeypot bot validation and event dispatching
 * - Interactive Breed Matcher Quiz rendering
 * - Programmatic Local SEO landing pages (Warsaw, Lodz, Torun, Bydgoszcz)
 */
class ReservationAndCroTest extends TestCase
{
    use RefreshDatabase;

    public function test_quick_callback_validates_required_phone(): void
    {
        $response = $this->postJson('/api/quick-callback', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['phone']);
    }

    public function test_quick_callback_validates_minimum_digits(): void
    {
        $response = $this->postJson('/api/quick-callback', [
            'phone' => '1234',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['phone']);
    }

    public function test_quick_callback_rejects_bot_with_honeypot(): void
    {
        $response = $this->postJson('/api/quick-callback', [
            'phone'       => '514 153 204',
            '_hp_website' => 'http://spam-bot.com',
        ]);

        $response->assertStatus(422);
    }

    public function test_quick_callback_succeeds_and_dispatches_event(): void
    {
        Event::fake([ContactMessageSent::class]);

        $response = $this->postJson('/api/quick-callback', [
            'phone'          => '514 153 204',
            'cat_name'       => 'Baron',
            'cat_breed'      => 'Kot Bengalski',
            'traffic_source' => 'FB_Ads_bengale',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        Event::assertDispatched(ContactMessageSent::class, function ($event) {
            return $event->data['phone'] === '514 153 204'
                && str_contains($event->data['subject'], 'Baron')
                && str_contains($event->data['message'], 'FB_Ads_bengale');
        });
    }

    public function test_quick_callback_delivers_notification_to_breeder_via_existing_mechanism(): void
    {
        \Illuminate\Support\Facades\Notification::fake();

        $response = $this->postJson('/api/quick-callback', [
            'phone'          => '+48 514 153 204',
            'cat_name'       => 'Baron',
            'cat_breed'      => 'Kot Bengalski',
            'traffic_source' => 'FB_Ads_bengale',
        ]);

        $response->assertOk();

        \Illuminate\Support\Facades\Notification::assertSentTo(
            new \Illuminate\Notifications\AnonymousNotifiable(),
            \App\Notifications\ContactMessageNotification::class,
            function ($notification) {
                return $notification->data['phone'] === '+48 514 153 204'
                    && str_contains($notification->data['subject'], 'Baron')
                    && str_contains($notification->data['message'], 'FB_Ads_bengale')
                    && str_contains($notification->data['message'], 'Data zgłoszenia:');
            }
        );
    }

    public function test_quick_callback_supports_international_phone_numbers(): void
    {
        $internationalNumbers = [
            '+49 151 2345678', // Germany
            '+44 7911 123456', // UK
            '+47 12 34 56 78', // Norway
            '+31 6 12345678',  // Netherlands
            '+1 555 123 4567', // US
        ];

        foreach ($internationalNumbers as $phone) {
            $response = $this->postJson('/api/quick-callback', [
                'phone' => $phone,
            ]);

            $response->assertOk();
            $response->assertJson(['success' => true]);
        }
    }

    public function test_reservation_modal_renders_international_country_selector(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('res-modal-country-code');
        $response->assertSee('+48');
        $response->assertSee('+49');
        $response->assertSee('+44');
        $response->assertSee('+47');
    }

    public function test_breed_matcher_quiz_page_renders_successfully(): void
    {
        $response = $this->get(route('quiz'));

        $response->assertOk();
        $response->assertSee('Dobierz Idealną Rasę w 45 Sekund');
        $response->assertSee('Jak wygląda Twój wymarzony dzień z kotem?');
        $response->assertSee('global-nav--dark');
        $response->assertSee('data-nav-theme="dark"', false);
        $response->assertSee('window.selectQuizOption', false);
        $response->assertSee('quizStep2');
        $response->assertSee('quizStep3');
        $response->assertSee('quizStep4');
        $response->assertSee('calculateWinningBreed', false);
        $response->assertSee('contactBreederFromQuiz', false);
        $response->assertSee('quizFutureNoticeForNow');
        $response->assertSee('quizAlternativesBox');
        $response->assertSee('quizAlternativeCatsGrid');
    }

    public function test_local_seo_city_pages_render_successfully(): void
    {
        $cities = [
            'seo.warszawa' => 'Warszawa',
            'seo.lodz' => 'Łódź',
            'seo.torun' => 'Toruń',
            'seo.wloclawek' => 'Włocławek',
            'seo.bydgoszcz' => 'Bydgoszcz',
        ];

        foreach ($cities as $routeName => $cityName) {
            $response = $this->get(route($routeName));
            $response->assertOk();
            $response->assertSee($cityName);
            $response->assertSee('Sikórz');
            $response->assertSee('Wideo-wizyta');
            $response->assertSee('Pytania i Odpowiedzi');
            $response->assertDontSee('ciasnych hodowli w miejskich blokach');
        }
    }
}
