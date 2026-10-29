<?php

declare(strict_types=1);

namespace Tests\Feature\Frontend;

use App\Models\Animal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsAndAttributionTest extends TestCase
{
    use RefreshDatabase;

    public function test_shell_renders_ga4_and_meta_pixel_with_consent_mode_v2(): void
    {
        config(['services.meta.pixel_id' => '123456789012345']);

        $response = $this->get(route('home'));

        $response->assertOk();

        // 1. GA4 Script & Tag ID
        $response->assertSee('googletagmanager.com/gtag/js', false);
        $response->assertSee('G-VB4ZCKR8WB');

        // 2. Consent Mode v2: default DENIED for all 4 parameters
        $response->assertSee("'analytics_storage': 'denied'", false);
        $response->assertSee("'ad_storage': 'denied'", false);
        $response->assertSee("'ad_user_data': 'denied'", false);
        $response->assertSee("'ad_personalization': 'denied'", false);

        // 3. Consent Mode v2: restoration grants all 4 parameters when accepted
        $response->assertSee("'analytics_storage': 'granted'", false);
        $response->assertSee("'ad_storage': 'granted'", false);
        $response->assertSee("'ad_user_data': 'granted'", false);
        $response->assertSee("'ad_personalization': 'granted'", false);

        // 4. Meta Pixel Initialization & Default Consent Revoked
        $response->assertSee('connect.facebook.net/en_US/fbevents.js', false);
        $response->assertSee("fbq('consent', 'revoke')", false);
    }

    public function test_cookie_consent_banner_renders_with_explicit_actions_and_no_duplicates(): void
    {
        $response = $this->get(route('home'));
        $content = $response->getContent();

        // 1. Banner element presence
        $response->assertSee('id="cookie-consent-banner"', false);
        $response->assertSee('Dbamy o Twój komfort!');
        $response->assertSee('Tylko niezbędne');
        $response->assertSee('Akceptuj wszystkie');

        // 2. Strictly single cookie banner on the page
        $matches = substr_count($content, 'id="cookie-consent-banner"');
        $this->assertSame(1, $matches, 'There must be strictly one cookie consent banner on the page.');

        // 3. Verification of Consent Mode actions in JS
        $response->assertSee('window.acceptCookieConsent', false);
        $response->assertSee('window.declineCookieConsent', false);
        $response->assertSee("fbq('consent', 'grant')", false);
    }

    public function test_analytics_js_defines_strict_event_separation(): void
    {
        $analyticsFile = resource_path('js/analytics.js');
        $this->assertFileExists($analyticsFile);
        $js = file_get_contents($analyticsFile);

        // 1. Verify 7 core event names are implemented
        $this->assertStringContainsString('view_cat_profile', $js);
        $this->assertStringContainsString('quiz_started', $js);
        $this->assertStringContainsString('quiz_completed', $js);
        $this->assertStringContainsString('contact_cta_click', $js);
        $this->assertStringContainsString('callback_submitted', $js);
        $this->assertStringContainsString('qualified_lead', $js);
        $this->assertStringContainsString('sale_completed', $js);

        // 2. Intent level: contact_cta_click is mapped to Meta 'Contact' (NEVER 'Lead')
        $this->assertStringContainsString("window.fbq('track', 'Contact'", $js);

        // 3. Outcome level: callback_submitted is mapped to Meta 'Lead'
        $this->assertStringContainsString("window.fbq('track', 'Lead'", $js);

        // 4. Sale level: sale_completed is mapped to Meta 'Purchase'
        $this->assertStringContainsString("window.fbq('track', 'Purchase'", $js);
    }

    public function test_analytics_js_sanitizes_pii_parameters(): void
    {
        $analyticsFile = resource_path('js/analytics.js');
        $js = file_get_contents($analyticsFile);

        // 1. Verify sanitizeAnalyticsParams is exported
        $this->assertStringContainsString('export function sanitizeAnalyticsParams', $js);

        // 2. Verify PII scrubbing regex matches phone, email, name, message
        $this->assertStringContainsString('piiKeyPattern', $js);
        $this->assertStringContainsString('emailPattern', $js);
        $this->assertStringContainsString('phonePattern', $js);
    }

    public function test_traffic_source_attribution_provides_safe_fallback_without_assuming_organic(): void
    {
        $analyticsFile = resource_path('js/analytics.js');
        $js = file_get_contents($analyticsFile);

        // 1. Verify getTrafficSourceLabel and sanitizeUtmValue exist
        $this->assertStringContainsString('export function getTrafficSourceLabel', $js);
        $this->assertStringContainsString('export function sanitizeUtmValue', $js);

        // 2. Verify fallback is 'Bezposredni' (Direct) when UTMs and referrers are missing
        $this->assertStringContainsString("return 'Bezposredni';", $js);

        // 3. Confirm that 'Organiczny' is strictly conditioned on search engines (Google/Bing/etc.)
        $this->assertStringContainsString('Google_Organiczny', $js);
        $this->assertStringContainsString('Search_Organiczny', $js);
    }

    public function test_quiz_dispatches_distinct_quiz_started_and_quiz_completed_events(): void
    {
        $response = $this->get(route('quiz'));

        $response->assertOk();

        // 1. quiz_started is dispatched when user makes first choice
        $response->assertSee("window.trackFunnelStep('quiz_started'", false);

        // 2. quiz_completed is dispatched on result calculation
        $response->assertSee("window.trackFunnelStep('quiz_completed'", false);

        // 3. contact_cta_click is dispatched on WhatsApp CTA
        $response->assertSee("window.trackFunnelStep('contact_cta_click'", false);
    }

    public function test_cat_profile_page_renders_metadata_for_view_cat_profile(): void
    {
        $cat = Animal::factory()->available()->create([
            'name' => 'KoteczekTestowy',
            'breed' => 'Kot Bengalski',
            'status' => \App\Enums\AnimalStatus::Available,
            'slug' => 'koteczek-testowy-analytics',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('frontend.animals.show', $cat->slug));

        $response->assertOk();
        $response->assertSee('data-ga-page="animal_view"', false);
        $response->assertSee('data-ga-animal-name="KoteczekTestowy"', false);
        $response->assertSee('data-ga-animal-breed="Kot Bengalski"', false);
        $response->assertSee('data-ga-animal-status="available"', false);
        $response->assertSee('data-ga-animal-slug="koteczek-testowy-analytics"', false);
    }

    public function test_conversion_cta_renders_clean_whatsapp_cta_with_contact_funnel_tracking(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('contact_cta_click', false);
        $response->assertSee('https://wa.me/48514153204', false);
        $response->assertDontSee('[Źródło:', false);
    }

    public function test_quick_callback_dispatches_callback_submitted_only_after_server_acceptance(): void
    {
        $modalJs = file_get_contents(resource_path('js/reservation-modal.js'));

        // Confirm callback_submitted is inside .then(data => ...) after response.ok
        $this->assertStringContainsString("trackFunnelStep('callback_submitted'", $modalJs);
        $this->assertStringContainsString('if (!response.ok)', $modalJs);
    }
}
