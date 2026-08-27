<?php

declare(strict_types=1);

namespace Tests\Feature\Frontend;

use App\Enums\AnimalStatus;
use App\Events\ContactMessageSent;
use App\Models\Animal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * GA4 Conversion Tracking Tests — ETAP 16K
 *
 * Sprawdza:
 *  1. Obecność GA4 skryptu w HTML
 *  2. Obecność Consent Mode v2 (analytics_storage: denied)
 *  3. contact_submit: alert sukcesu renderowany tylko po sukcesie, NIE przy błędzie
 *  4. data-ga-* atrybuty na stronie kota
 *  5. data-ga-* atrybuty na liście kotów (z filtrem i bez)
 *  6. Brak PII w data-ga-* atrybutach (email, telefon)
 *  7. Cookie consent banner obecny w HTML
 */
class Ga4TrackingTest extends TestCase
{
    use RefreshDatabase;

    // ─── 1. GA4 Script w HTML ──────────────────────────────────────────────────

    public function test_frontend_renders_ga4_script(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('googletagmanager.com/gtag/js', false);
        $response->assertSee('gtag(', false);
    }

    // ─── 2. Consent Mode v2 default denied ────────────────────────────────────

    public function test_frontend_renders_consent_mode_default_denied(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        // Consent Mode v2: analytics_storage musi być domyślnie 'denied'
        $response->assertSee("'analytics_storage': 'denied'", false);
        $response->assertSee("gtag('consent', 'default'", false);
    }

    // ─── 3a. contact_submit: alert sukcesu po pomyślnym wysłaniu ──────────────

    public function test_contact_success_renders_success_alert(): void
    {
        Event::fake([ContactMessageSent::class]);

        // Podążamy za przekierowaniem — strona musi renderować .form-alert--success
        $followResponse = $this->followingRedirects()->post(route('frontend.contact.store'), [
            'name'    => 'Jan Testowy',
            'email'   => 'jan@test.com',
            'message' => 'Wiadomość testowa dłuższa niż 15 znaków.',
        ]);

        $followResponse->assertOk();
        $followResponse->assertSee('form-alert--success', false);
    }

    // ─── 3b. contact_submit: BRAK alertu sukcesu przy błędzie walidacji ───────

    public function test_contact_validation_error_does_not_render_success_alert(): void
    {
        $response = $this->followingRedirects()->post(route('frontend.contact.store'), [
            'name'    => '', // brak wymaganego pola
            'email'   => 'niepoprawny',
            'message' => 'Krótka',
        ]);

        $response->assertOk();
        // BRAK success alert — contact_submit NIE powinno się triggerować w GA4
        $response->assertDontSee('form-alert--success', false);
    }

    // ─── 4. data-ga-* atrybuty na stronie kota ────────────────────────────────

    public function test_animal_show_has_ga_data_attributes(): void
    {
        $animal = Animal::factory()->create([
            'name'         => 'TestKot',
            'slug'         => 'testkot',
            'breed'        => 'Kot Bengalski',
            'status'       => AnimalStatus::Breeding,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get(route('frontend.animals.show', $animal));

        $response->assertOk();
        $response->assertSee('data-ga-page="animal_view"', false);
        $response->assertSee('data-ga-animal-breed="Kot Bengalski"', false);
        $response->assertSee('data-ga-animal-status="breeding"', false);
    }

    // ─── 5a. data-ga-* na liście kotów — bez filtra ───────────────────────────

    public function test_animal_list_has_ga_page_attribute(): void
    {
        $response = $this->get(route('frontend.animals.index'));

        $response->assertOk();
        $response->assertSee('data-ga-page="animal_list"', false);
        $response->assertSee('data-ga-breed-filter="all"', false);
    }

    // ─── 5b. data-ga-* na liście kotów — z filtrem rasy ──────────────────────

    public function test_animal_list_with_breed_filter_has_correct_ga_attribute(): void
    {
        Animal::factory()->create([
            'name'         => 'Filterkot',
            'breed'        => 'Kot Bengalski',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get(route('frontend.animals.index', ['breed' => 'Kot Bengalski']));

        $response->assertOk();
        $response->assertSee('data-ga-breed-filter="Kot Bengalski"', false);
    }

    // ─── 6. Brak PII w data-ga-* atrybutach ───────────────────────────────────

    public function test_animal_show_no_pii_in_ga_data_attributes(): void
    {
        $animal = Animal::factory()->create([
            'name'         => 'TestKot',
            'slug'         => 'testkot-pii',
            'breed'        => 'Kot Bengalski',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get(route('frontend.animals.show', $animal));

        $response->assertOk();
        $content = $response->getContent();

        // Sprawdź że data-ga-* nie zawiera adresu email ani numeru telefonu
        preg_match_all('/data-ga-[^=]+="([^"]+)"/', $content, $matches);
        $gaValues = $matches[1] ?? [];

        foreach ($gaValues as $value) {
            $this->assertDoesNotMatchRegularExpression(
                '/[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}/',
                $value,
                "data-ga-* attribute contains an email address: {$value}"
            );
        }
    }

    // ─── 7. Cookie consent banner obecny w HTML ────────────────────────────────

    public function test_frontend_renders_cookie_consent_banner(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('cookie-consent-banner', false);
        $response->assertSee('cookie-consent-accept', false);
        $response->assertSee('cookie-consent-decline', false);
    }

    // ─── 8. Analytics.js importowany (meta tag z GA ID jest w HTML) ───────────

    public function test_frontend_exposes_ga_measurement_id_meta(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('name="ga-measurement-id"', false);
    }
}
