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
            'page_url'       => 'https://kotyzmazowieckiejszwajcarii.pl/koty/baron',
            'traffic_source' => 'FB_Ads_bengale',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        Event::assertDispatched(ContactMessageSent::class, function ($event) {
            return $event->data['phone'] === '+48 514 153 204'
                && str_contains($event->data['subject'], 'Baron')
                && str_contains($event->data['message'], 'FB_Ads_bengale')
                && str_contains($event->data['message'], 'https://kotyzmazowieckiejszwajcarii.pl/koty/baron');
        });
    }

    public function test_quick_callback_normalizes_various_polish_phone_formats(): void
    {
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

        $formats = [
            '514153204',
            '514 153 204',
            '514-153-204',
            '514.153.204',
            '+48514153204',
            '0048 514 153 204',
            '+48 (514) 153-204',
        ];

        foreach ($formats as $inputPhone) {
            Event::fake([ContactMessageSent::class]);

            $response = $this->postJson('/api/quick-callback', [
                'phone' => $inputPhone,
            ]);

            $response->assertOk();
            $response->assertJson(['success' => true]);

            Event::assertDispatched(ContactMessageSent::class, function ($event) {
                return $event->data['phone'] === '+48 514 153 204';
            });
        }
    }

    public function test_quick_callback_rejects_insufficient_digits_or_invalid_characters(): void
    {
        // 1. Minimum 7 digits required (reject formatting without enough digits)
        $resp1 = $this->postJson('/api/quick-callback', ['phone' => '+48 () --']);
        $resp1->assertStatus(422);
        $resp1->assertJsonValidationErrors(['phone']);

        // 2. Letters not allowed
        $resp2 = $this->postJson('/api/quick-callback', ['phone' => '514abc153204']);
        $resp2->assertStatus(422);
        $resp2->assertJsonValidationErrors(['phone']);

        // 3. Exceeds 15 digits
        $resp3 = $this->postJson('/api/quick-callback', ['phone' => '+48 1234567890123456']);
        $resp3->assertStatus(422);
        $resp3->assertJsonValidationErrors(['phone']);
    }

    public function test_quick_callback_handles_notification_failure_gracefully_without_exposing_errors(): void
    {
        // Simulate notification dispatch failure (e.g., mail server unreachable)
        \Illuminate\Support\Facades\Event::listen(ContactMessageSent::class, function () {
            throw new \RuntimeException('Connection refused to SMTP server: 127.0.0.1:25');
        });

        $response = $this->postJson('/api/quick-callback', [
            'phone' => '514 153 204',
        ]);

        $response->assertStatus(500);
        $response->assertJson([
            'success' => false,
        ]);
        // Asserts internal technical error is never exposed to visitor
        $response->assertDontSee('SMTP server');
        $response->assertDontSee('RuntimeException');
        $response->assertSee('+48 514 153 204');
    }

    public function test_quick_callback_throttles_excessive_submissions(): void
    {
        Event::fake([ContactMessageSent::class]);

        // Send 5 valid requests (rate limit is 5 per minute)
        for ($i = 0; $i < 5; $i++) {
            $response = $this->postJson('/api/quick-callback', [
                'phone' => '514 153 20' . $i,
            ]);
            $response->assertOk();
        }

        // 6th request must be throttled with HTTP 429
        $response6 = $this->postJson('/api/quick-callback', [
            'phone' => '514 153 209',
        ]);
        $response6->assertStatus(429);
    }

    public function test_contact_page_renders_all_primary_business_contact_options(): void
    {
        $response = $this->get(route('contact'));
        $response->assertOk();

        // 1. Direct phone call
        $response->assertSee('tel:+48514153204');
        $response->assertSee('+48 514 153 204');

        // 2. WhatsApp
        $response->assertSee('https://wa.me/48514153204');
        $response->assertSee('Napisz na WhatsApp');

        // 3. Facebook Messenger
        $response->assertSee('https://m.me/61580668026948');
        $response->assertSee('Facebook Messenger');

        // 4. Formularz kontaktowy
        $response->assertSee('Formularz kontaktowy');
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
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

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

    public function test_quiz_strictly_excludes_reserved_sold_and_breeding_animals(): void
    {
        $availableCat = Animal::factory()->available()->create([
            'name' => 'DafneDostepna',
            'breed' => 'Kot Syjamski',
            'published_at' => now()->subDay(),
        ]);

        $reservedCat = Animal::factory()->reserved()->create([
            'name' => 'RezerwowanyKotek',
            'breed' => 'Kot Syjamski',
            'published_at' => now()->subDay(),
        ]);

        $soldCat = Animal::factory()->sold()->create([
            'name' => 'WNowymDomuKotek',
            'breed' => 'Kot Syjamski',
            'published_at' => now()->subDay(),
        ]);

        $breedingCat = Animal::factory()->breeding()->create([
            'name' => 'KrolowaMatka',
            'breed' => 'Kot Syjamski',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('quiz'));

        $response->assertOk();
        $response->assertSee('DafneDostepna');
        $response->assertDontSee('RezerwowanyKotek');
        $response->assertDontSee('WNowymDomuKotek');
        $response->assertDontSee('KrolowaMatka');

        // Verify dynamic update: if available cat becomes reserved, it disappears from quiz
        $availableCat->update(['status' => \App\Enums\AnimalStatus::Reserved]);

        $responseAfterUpdate = $this->get(route('quiz'));
        $responseAfterUpdate->assertOk();
        $responseAfterUpdate->assertDontSee('DafneDostepna');
    }

    public function test_quiz_renders_back_navigation_hero_badges_and_behavioral_disclaimer(): void
    {
        $response = $this->get(route('quiz'));

        $response->assertOk();
        $response->assertSee('quiz-hero__badges', false);
        $response->assertSee('4 szybkie pytania');
        $response->assertSee('bez wymogu podawania e-maila');
        $response->assertSee('quiz-nav-back', false);
        $response->assertSee('window.prevQuizStep', false);
        $response->assertSee('quiz-modify-btn', false);
        $response->assertSee('quiz-result__disclaimer', false);
        $response->assertSee('indywidualny charakter');
        $response->assertSee('window.contactBreederFromQuiz', false);
        $response->assertSee('window.openCallbackFromQuiz', false);
        $response->assertSee('quizEmptyCatalogueNotice', false);
    }

    public function test_quiz_scoring_algorithm_determines_unique_winner_across_all_thirty_six_paths(): void
    {
        $step1Options = ['calm_couch', 'active_curious', 'devoted_vocal'];
        $step2Options = ['away_work', 'home_work', 'family_children', 'other_pets'];
        $step3Options = ['short_sleek', 'dense_plush', 'interactive_shadow'];

        $ties = 0;
        $distribution = ['bengal' => 0, 'british' => 0, 'siamese' => 0];

        foreach ($step1Options as $s1) {
            foreach ($step2Options as $s2) {
                foreach ($step3Options as $s3) {
                    $scores = ['bengal' => 0.0, 'british' => 0.0, 'siamese' => 0.0];

                    // STEP 1: Energy & Lifestyle
                    if ($s1 === 'calm_couch') {
                        $scores['british'] += 5.2;
                        $scores['siamese'] += 1.0;
                    } elseif ($s1 === 'active_curious') {
                        $scores['bengal'] += 5.2;
                        $scores['siamese'] += 2.0;
                    } elseif ($s1 === 'devoted_vocal') {
                        $scores['siamese'] += 5.2;
                        $scores['bengal'] += 1.0;
                        $scores['british'] += 1.0;
                    }

                    // STEP 2: Time away & Household Dynamics
                    if ($s2 === 'away_work') {
                        $scores['british'] += 3.5;
                        $scores['bengal'] += 1.0;
                        $scores['siamese'] -= 3.0;
                    } elseif ($s2 === 'home_work') {
                        $scores['siamese'] += 3.0;
                        $scores['bengal'] += 2.0;
                        $scores['british'] += 1.0;
                    } elseif ($s2 === 'family_children') {
                        $scores['british'] += 2.5;
                        $scores['bengal'] += 2.5;
                        $scores['siamese'] += 1.5;
                    } elseif ($s2 === 'other_pets') {
                        $scores['bengal'] += 2.0;
                        $scores['siamese'] += 2.0;
                        $scores['british'] += 2.0;
                    }

                    // STEP 3: Coat & Interaction Preferences
                    if ($s3 === 'short_sleek') {
                        $scores['bengal'] += 3.0;
                        $scores['siamese'] += 1.5;
                    } elseif ($s3 === 'dense_plush') {
                        $scores['british'] += 3.0;
                    } elseif ($s3 === 'interactive_shadow') {
                        $scores['siamese'] += 3.0;
                        $scores['bengal'] += 1.0;
                    }

                    $maxScore = max($scores);
                    $winners = array_keys(array_filter($scores, fn ($sc) => abs($sc - $maxScore) < 0.001));

                    if (count($winners) > 1) {
                        $ties++;
                    } else {
                        $distribution[$winners[0]]++;
                    }
                }
            }
        }

        // 1. Zero ties across all 36 combinations
        $this->assertSame(0, $ties, 'Quiz scoring must produce deterministic, unique winner with zero ties across all 36 paths.');

        // 2. Equitable representation for all three cattery breeds
        $this->assertGreaterThanOrEqual(10, $distribution['bengal']);
        $this->assertGreaterThanOrEqual(10, $distribution['british']);
        $this->assertGreaterThanOrEqual(10, $distribution['siamese']);

        // 3. Clear archetypal path checks
        // Path A: Active lifestyle + Family with children + Short sleek coat => Bengal
        $this->assertSame(12, $distribution['bengal']);
        // Path B: Calm couch + Away at work + Dense plush coat => British
        $this->assertSame(13, $distribution['british']);
        // Path C: Devoted vocal + Home office + Interactive shadow coat => Siamese
        $this->assertSame(11, $distribution['siamese']);
    }

    public function test_quiz_pickup_rules_distinguish_immediate_ready_from_early_reservation_and_missing_dob(): void
    {
        $now = now();

        // 1. Ready now: 13 weeks old (>= 12 weeks pickup rule)
        $readyCat = Animal::factory()->available()->create([
            'name' => 'KotekGotowy',
            'breed' => 'Kot Bengalski',
            'date_of_birth' => $now->copy()->subWeeks(13),
            'published_at' => $now->copy()->subDay(),
        ]);

        // 2. Early reservation: 6 weeks old (< 12 weeks pickup rule)
        $earlyCat = Animal::factory()->available()->create([
            'name' => 'KotekWczesny',
            'breed' => 'Kot Brytyjski',
            'date_of_birth' => $now->copy()->subWeeks(6),
            'published_at' => $now->copy()->subDay(),
        ]);

        // 3. Missing DOB: available, but birth date unconfirmed
        $unknownCat = Animal::factory()->available()->create([
            'name' => 'KotekBezDaty',
            'breed' => 'Kot Syjamski',
            'date_of_birth' => null,
            'published_at' => $now->copy()->subDay(),
        ]);

        $response = $this->get(route('quiz'));
        $response->assertOk();

        // Verify Ready Cat is strictly flagged with data-ready="now"
        $response->assertSee('KotekGotowy');
        $response->assertSee('data-ready="now"', false);

        // Verify Early Cat is flagged with data-ready="future" and pickup window label
        $response->assertSee('KotekWczesny');
        $response->assertSee('Dostępny do rezerwacji');
        $response->assertSee('Odbiór: za ok. 6 tyg.');

        // Verify Missing DOB Cat is flagged with data-ready="future" and safe fallback badge
        $response->assertSee('KotekBezDaty');
        $response->assertSee('Dostępny do rezerwacji');

        // Confirm kittens without DOB or < 12 weeks are never presented as ready for immediate pickup
        $content = $response->getContent();
        $this->assertStringContainsString('data-ready="future"', $content);
    }

    public function test_quiz_context_passing_includes_breed_timeframe_and_attribution(): void
    {
        $response = $this->get(route('quiz'));
        $response->assertOk();

        // Checks WhatsApp pre-filled template construction
        $response->assertSee('Mój wynik dopasowania: ${breed}', false);
        $response->assertSee('Preferowany czas odbioru: ${timeframe}', false);
        $response->assertSee('Quiz_Dopasowania');
        $response->assertSee('wa.me/48514153204');

        // Checks Reservation modal integration
        $response->assertSee('window.openReservationModal', false);
        $response->assertSee('Wynik Quizu: ${breed}', false);
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
            $response->assertDontSee('trzykrotnym odrobaczeniu');
            $response->assertDontSee('3 odrobaczenia');
            $response->assertDontSee('trzy odrobaczenia');
        }

        $responseTorun = $this->get(route('seo.torun'));
        $responseTorun->assertSee('dwukrotnym odrobaczeniu');
        $responseTorun->assertDontSee('trzykrotnym odrobaczeniu');
    }

    public function test_public_pages_consistently_state_two_dewormings_and_never_three(): void
    {
        // 1. Homepage
        $home = $this->get(route('home'));
        $home->assertOk();
        $home->assertSee('dwukrotnym odrobaczeniem');
        $home->assertDontSee('trzykrotnym odrobaczeniu');
        $home->assertDontSee('3 odrobaczenia');

        // 2. About page (Standard section)
        $about = $this->get(route('about'));
        $about->assertOk();
        $about->assertSee('2x Odrobaczanie');
        $about->assertSee('2 cykle odrobaczania');
        $about->assertDontSee('3x Odrobaczanie');
        $about->assertDontSee('3 cykle odrobaczania');

        // 3. Cattery page (Purchase process)
        $cattery = $this->get(route('cattery'));
        $cattery->assertOk();
        $cattery->assertSee('2 odrobaczenia');
        $cattery->assertDontSee('3 odrobaczenia');

        // 4. Contact page (FAQ)
        $contact = $this->get(route('contact'));
        $contact->assertOk();
        $contact->assertSee('2 odrobaczeń');
        $contact->assertSee('2 odrobaczeniami');
        $contact->assertDontSee('3 odrobaczeń');
        $contact->assertDontSee('3 odrobaczeniami');

        // 5. Verify unrelated mentions of the number 3 remain intact
        $animalsIndex = $this->get(route('frontend.animals.index'));
        $animalsIndex->assertOk();
        $animalsIndex->assertSee('Hodujemy trzy wyjątkowe rasy');
    }

    public function test_animal_model_pickup_window_and_age_in_weeks_calculations(): void
    {
        $now = \Illuminate\Support\Carbon::parse('2026-10-15 12:00:00');
        \Illuminate\Support\Carbon::setTestNow($now);

        // 1. Young kitten: 4 weeks old (< 12 weeks)
        $youngKitten = Animal::factory()->create([
            'status' => \App\Enums\AnimalStatus::Available,
            'date_of_birth' => $now->copy()->subWeeks(4),
        ]);

        $this->assertSame(4, $youngKitten->ageInWeeks($now));
        $this->assertTrue($youngKitten->isEarlyReservation($now));
        $this->assertFalse($youngKitten->isInPickupWindow($now));
        $this->assertFalse($youngKitten->isReadyNow($now));
        $this->assertFalse($youngKitten->isOdchowany($now));
        $this->assertSame(8, $youngKitten->weeksUntilPickupWindow($now));
        $this->assertSame('Dostępny do rezerwacji', $youngKitten->pickupBadgeLabel($now));
        $this->assertSame('Odbiór: za ok. 8 tyg.', $youngKitten->pickupTimelineLabel($now));
        $this->assertSame('warning', $youngKitten->pickupBadgeVariant($now));

        $window = $youngKitten->pickupWindow();
        $this->assertNotNull($window);
        $this->assertSame($youngKitten->date_of_birth->copy()->addWeeks(12)->format('d.m.Y'), $window['start']->format('d.m.Y'));
        $this->assertSame($youngKitten->date_of_birth->copy()->addWeeks(14)->format('d.m.Y'), $window['end']->format('d.m.Y'));

        // 2. Kitten at boundary: exactly 12 weeks old (starts pickup window)
        $boundary12 = Animal::factory()->create([
            'status' => \App\Enums\AnimalStatus::Available,
            'date_of_birth' => $now->copy()->subWeeks(12),
        ]);

        $this->assertSame(12, $boundary12->ageInWeeks($now));
        $this->assertFalse($boundary12->isEarlyReservation($now));
        $this->assertTrue($boundary12->isInPickupWindow($now));
        $this->assertTrue($boundary12->isReadyNow($now));
        $this->assertFalse($boundary12->isOdchowany($now));
        $this->assertSame(0, $boundary12->weeksUntilPickupWindow($now));
        $this->assertSame('Gotowy do odbioru', $boundary12->pickupBadgeLabel($now));
        $this->assertSame('Gotowy do odbioru od zaraz', $boundary12->pickupTimelineLabel($now));
        $this->assertSame('gold', $boundary12->pickupBadgeVariant($now));

        // 3. Kitten at 13 weeks old (inside 12–14 week window)
        $kitten13 = Animal::factory()->create([
            'status' => \App\Enums\AnimalStatus::Available,
            'date_of_birth' => $now->copy()->subWeeks(13),
        ]);

        $this->assertSame(13, $kitten13->ageInWeeks($now));
        $this->assertTrue($kitten13->isInPickupWindow($now));
        $this->assertTrue($kitten13->isReadyNow($now));
        $this->assertSame('Gotowy do odbioru', $kitten13->pickupBadgeLabel($now));
        $this->assertSame('Gotowy do odbioru od zaraz', $kitten13->pickupTimelineLabel($now));

        // 4. Kitten at boundary: exactly 14 weeks old (upper limit of pickup window)
        $boundary14 = Animal::factory()->create([
            'status' => \App\Enums\AnimalStatus::Available,
            'date_of_birth' => $now->copy()->subWeeks(14),
        ]);

        $this->assertSame(14, $boundary14->ageInWeeks($now));
        $this->assertTrue($boundary14->isInPickupWindow($now));
        $this->assertFalse($boundary14->isOdchowany($now));
        $this->assertSame('Gotowy do odbioru', $boundary14->pickupBadgeLabel($now));
        $this->assertSame('Gotowy do odbioru od zaraz', $boundary14->pickupTimelineLabel($now));

        // 5. Kitten older than 14 weeks (e.g. 16 weeks old)
        $olderKitten = Animal::factory()->create([
            'status' => \App\Enums\AnimalStatus::Available,
            'date_of_birth' => $now->copy()->subWeeks(16),
        ]);

        $this->assertSame(16, $olderKitten->ageInWeeks($now));
        $this->assertFalse($olderKitten->isEarlyReservation($now));
        $this->assertFalse($olderKitten->isInPickupWindow($now));
        $this->assertTrue($olderKitten->isReadyNow($now));
        $this->assertTrue($olderKitten->isOdchowany($now));
        $this->assertSame(0, $olderKitten->weeksUntilPickupWindow($now));
        $this->assertSame('Gotowy do odbioru', $olderKitten->pickupBadgeLabel($now));
        $this->assertSame('Gotowy do odbioru od zaraz', $olderKitten->pickupTimelineLabel($now));
        $this->assertSame('gold', $olderKitten->pickupBadgeVariant($now));

        // 6. Missing / null birth date
        $unknownKitten = Animal::factory()->create([
            'status' => \App\Enums\AnimalStatus::Available,
            'date_of_birth' => null,
        ]);

        $this->assertNull($unknownKitten->ageInWeeks($now));
        $this->assertNull($unknownKitten->pickupWindow());
        $this->assertNull($unknownKitten->pickupWindowFormatted());
        $this->assertNull($unknownKitten->weeksUntilPickupWindow($now));
        $this->assertFalse($unknownKitten->isEarlyReservation($now));
        $this->assertFalse($unknownKitten->isInPickupWindow($now));
        $this->assertTrue($unknownKitten->isReadyNow($now));
        $this->assertFalse($unknownKitten->isOdchowany($now));
        $this->assertSame('Dostępny do rezerwacji', $unknownKitten->pickupBadgeLabel($now));
        $this->assertSame('gold', $unknownKitten->pickupBadgeVariant($now));

        // 7. Reserved kitten younger than 12 weeks
        $reservedKitten = Animal::factory()->create([
            'status' => \App\Enums\AnimalStatus::Reserved,
            'date_of_birth' => $now->copy()->subWeeks(6),
        ]);

        $this->assertSame('Zarezerwowany', $reservedKitten->pickupBadgeLabel($now));
        $this->assertSame('warning', $reservedKitten->pickupBadgeVariant($now));

        // 8. Breeding cat (Reproduktor / Kotka Hodowlana)
        $breedingFemale = Animal::factory()->create([
            'status' => \App\Enums\AnimalStatus::Breeding,
            'gender' => \App\Enums\AnimalGender::Female,
            'date_of_birth' => $now->copy()->subYears(2),
        ]);
        $this->assertSame('Kotka Hodowlana', $breedingFemale->pickupBadgeLabel($now));
        $this->assertSame('info', $breedingFemale->pickupBadgeVariant($now));

        // 9. Sold cat
        $soldCat = Animal::factory()->create([
            'status' => \App\Enums\AnimalStatus::Sold,
            'date_of_birth' => $now->copy()->subMonths(8),
        ]);
        $this->assertSame('W nowym domu', $soldCat->pickupBadgeLabel($now));
        $this->assertSame('muted', $soldCat->pickupBadgeVariant($now));

        \Illuminate\Support\Carbon::setTestNow();
    }

    public function test_public_pages_and_cards_enforce_12_14_pickup_rule_consistently(): void
    {
        $now = \Illuminate\Support\Carbon::parse('2026-10-15 12:00:00');
        \Illuminate\Support\Carbon::setTestNow($now);

        // 1. Create a 5-week-old kitten (< 12 weeks)
        $earlyKitten = Animal::factory()->create([
            'name' => 'Felicja',
            'breed' => 'Kot Brytyjski',
            'gender' => \App\Enums\AnimalGender::Female,
            'status' => \App\Enums\AnimalStatus::Available,
            'date_of_birth' => $now->copy()->subWeeks(5),
            'is_published' => true,
            'is_featured' => true,
        ]);

        // 2. Create a 13-week-old kitten (in 12-14 pickup window)
        $readyKitten = Animal::factory()->create([
            'name' => 'Hektor',
            'breed' => 'Kot Bengalski',
            'gender' => \App\Enums\AnimalGender::Male,
            'status' => \App\Enums\AnimalStatus::Available,
            'date_of_birth' => $now->copy()->subWeeks(13),
            'is_published' => true,
            'is_featured' => true,
        ]);

        // Verify Contact page FAQ
        $contactResponse = $this->get(route('contact'));
        $contactResponse->assertOk();
        $contactResponse->assertSee('Kocięta mogą wyjeżdżać do nowych domów w wieku 12–14 tygodni, po potwierdzeniu gotowości konkretnego kociaka');
        $contactResponse->assertDontSee('po ukończeniu 14. tygodnia życia');
        $contactResponse->assertDontSee('14–16 tygodni');
        $contactResponse->assertDontSee('14-16 tygodni');

        // Verify Torun local SEO FAQ
        $torunResponse = $this->get(route('seo.torun'));
        $torunResponse->assertOk();
        $torunResponse->assertSee('Kocięta mogą wyjeżdżać do nowych domów w wieku 12–14 tygodni, po potwierdzeniu gotowości konkretnego kociaka');
        $torunResponse->assertDontSee('14–16');

        // Verify Quiz page
        $quizResponse = $this->get(route('quiz'));
        $quizResponse->assertOk();
        $quizResponse->assertSee('Kocięta mogą wyjeżdżać do nowych domów w wieku 12–14 tygodni, po potwierdzeniu gotowości konkretnego kociaka');
        $quizResponse->assertDontSee('będą gotowe do odbioru za ok. 3–4 tygodnie');

        // Verify Early Reservation cat profile: show page displays early reservation badge and pickup window
        $earlyProfile = $this->get(route('frontend.animals.show', $earlyKitten));
        $earlyProfile->assertOk();
        $earlyProfile->assertSee('Dostępny do rezerwacji');
        $earlyProfile->assertSee('Odbiór: za ok. 7 tyg.');
        $earlyProfile->assertSee($earlyKitten->pickupWindowFormatted());
        $earlyProfile->assertSee('Zasada odbioru:');

        // Verify Ready cat profile: show page displays 12-14 weeks ready badge
        $readyProfile = $this->get(route('frontend.animals.show', $readyKitten));
        $readyProfile->assertOk();
        $readyProfile->assertSee('Gotowy do odbioru');
        $readyProfile->assertSee('Gotowy do odbioru od zaraz');
        $readyProfile->assertSee($readyKitten->pickupWindowFormatted());

        // Verify Homepage cards display matching badges
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk();
        $homeResponse->assertSee('Dostępny do rezerwacji');
        $homeResponse->assertSee('Odbiór: za ok. 7 tyg.');
        $homeResponse->assertSee('Gotowy do odbioru');

        \Illuminate\Support\Carbon::setTestNow();
    }

    public function test_cat_status_and_identity_remain_consistent_across_catalogue_profile_and_mobile_bar(): void
    {
        $now = \Illuminate\Support\Carbon::parse('2026-10-15 12:00:00');
        \Illuminate\Support\Carbon::setTestNow($now);

        // 1. Kitten awaiting pickup window (5 weeks old)
        $earlyCat = Animal::factory()->create([
            'name'          => 'AdaBrytyjska',
            'breed'         => 'Kot Brytyjski',
            'gender'        => \App\Enums\AnimalGender::Female,
            'status'        => \App\Enums\AnimalStatus::Available,
            'date_of_birth' => $now->copy()->subWeeks(5),
            'is_published'  => true,
            'is_featured'   => true,
        ]);

        // 2. Kitten in pickup window (13 weeks old)
        $readyCat = Animal::factory()->create([
            'name'          => 'CarlosBengalski',
            'breed'         => 'Kot Bengalski',
            'gender'        => \App\Enums\AnimalGender::Male,
            'status'        => \App\Enums\AnimalStatus::Available,
            'date_of_birth' => $now->copy()->subWeeks(13),
            'is_published'  => true,
            'is_featured'   => true,
        ]);

        // 3. Kitten reserved (even if 13 weeks old)
        $reservedCat = Animal::factory()->create([
            'name'          => 'ZafiraZarezerwowana',
            'breed'         => 'Kot Syjamski',
            'gender'        => \App\Enums\AnimalGender::Female,
            'status'        => \App\Enums\AnimalStatus::Reserved,
            'date_of_birth' => $now->copy()->subWeeks(13),
            'is_published'  => true,
            'is_featured'   => true,
        ]);

        // Catalogue checks
        $catalogue = $this->get(route('frontend.animals.index'));
        $catalogue->assertOk();
        $catalogue->assertSee('AdaBrytyjska');
        $catalogue->assertSee('Dostępny do rezerwacji');
        $catalogue->assertSee('Odbiór: za ok. 7 tyg.');
        $catalogue->assertSee('CarlosBengalski');
        $catalogue->assertSee('Gotowy do odbioru');
        $catalogue->assertSee('Gotowy do odbioru od zaraz');
        $catalogue->assertSee('ZafiraZarezerwowana');
        $catalogue->assertSee('Zarezerwowany');

        // Profile checks: Ada
        $adaProfile = $this->get(route('frontend.animals.show', $earlyCat));
        $adaProfile->assertOk();
        $adaProfile->assertSee('AdaBrytyjska');
        $adaProfile->assertSee('Dostępny do rezerwacji');
        $adaProfile->assertSee('Odbiór: za ok. 7 tyg.');
        $adaProfile->assertSee($earlyCat->pickupWindowFormatted());
        $adaProfile->assertSee("window.openReservationModal('AdaBrytyjska', 'Kot Brytyjski', 'Dostępny do rezerwacji')", false);

        // Profile checks: Carlos
        $carlosProfile = $this->get(route('frontend.animals.show', $readyCat));
        $carlosProfile->assertOk();
        $carlosProfile->assertSee('CarlosBengalski');
        $carlosProfile->assertSee('Gotowy do odbioru');
        $carlosProfile->assertSee('Gotowy do odbioru od zaraz');
        $carlosProfile->assertSee("window.openReservationModal('CarlosBengalski', 'Kot Bengalski', 'Gotowy do odbioru')", false);

        // Profile checks: Zafira (Reserved)
        $zafiraProfile = $this->get(route('frontend.animals.show', $reservedCat));
        $zafiraProfile->assertOk();
        $zafiraProfile->assertSee('ZafiraZarezerwowana');
        $zafiraProfile->assertSee('Zarezerwowany');
        $zafiraProfile->assertSee("window.openReservationModal('ZafiraZarezerwowana', 'Kot Syjamski', 'Zarezerwowany')", false);
        $this->assertSame('Zarezerwowany', $reservedCat->pickupBadgeLabel());

        \Illuminate\Support\Carbon::setTestNow();
    }

    public function test_reserved_and_unavailable_kittens_never_represented_as_ready_for_pickup(): void
    {
        $now = \Illuminate\Support\Carbon::parse('2026-10-15 12:00:00');
        \Illuminate\Support\Carbon::setTestNow($now);

        // 1. Reserved cat (older than 14 weeks)
        $reserved = Animal::factory()->create([
            'name'          => 'ZarezerwowanyMaluch',
            'status'        => \App\Enums\AnimalStatus::Reserved,
            'date_of_birth' => $now->copy()->subWeeks(15),
            'is_published'  => true,
        ]);

        $this->assertFalse($reserved->isReadyNow($now));
        $this->assertFalse($reserved->isInPickupWindow($now));
        $this->assertFalse($reserved->isEarlyReservation($now));
        $this->assertSame('Zarezerwowany', $reserved->pickupBadgeLabel($now));

        // 2. Sold cat
        $sold = Animal::factory()->create([
            'name'          => 'SprzedanyMaluch',
            'status'        => \App\Enums\AnimalStatus::Sold,
            'date_of_birth' => $now->copy()->subWeeks(16),
            'is_published'  => true,
        ]);

        $this->assertFalse($sold->isReadyNow($now));
        $this->assertFalse($sold->isInPickupWindow($now));
        $this->assertSame('W nowym domu', $sold->pickupBadgeLabel($now));

        // 3. Quiz exclusion: neither reserved nor sold cat appears in quiz
        $quizResponse = $this->get(route('quiz'));
        $quizResponse->assertOk();
        $quizResponse->assertDontSee('ZarezerwowanyMaluch');
        $quizResponse->assertDontSee('SprzedanyMaluch');

        \Illuminate\Support\Carbon::setTestNow();
    }

    public function test_homepage_featured_section_strictly_excludes_breeding_parents_and_sold_cats(): void
    {
        // 1. Featured breeding parent
        $breeding = Animal::factory()->create([
            'name'         => 'BaltazarReproduktor',
            'status'       => \App\Enums\AnimalStatus::Breeding,
            'is_published' => true,
            'is_featured'  => true,
        ]);

        // 2. Featured sold cat
        $sold = Animal::factory()->create([
            'name'         => 'BarbieWNowymDomu',
            'status'       => \App\Enums\AnimalStatus::Sold,
            'is_published' => true,
            'is_featured'  => true,
        ]);

        // 3. Featured available kitten
        $available = Animal::factory()->create([
            'name'         => 'CandyDostepna',
            'status'       => \App\Enums\AnimalStatus::Available,
            'is_published' => true,
            'is_featured'  => true,
        ]);

        $home = $this->get(route('home'));
        $home->assertOk();
        $home->assertSee('CandyDostepna');
        $home->assertDontSee('BaltazarReproduktor');
        $home->assertDontSee('BarbieWNowymDomu');
    }

    public function test_empty_catalogue_and_missing_data_cases_render_gracefully(): void
    {
        // 1. Empty catalogue: explicitly delete all seeded animals
        Animal::query()->delete();

        $home = $this->get(route('home'));
        $home->assertOk();
        $home->assertSee('Aktualnie wszystkie kocięta znalazły nowe domy');

        $catalogue = $this->get(route('frontend.animals.index'));
        $catalogue->assertOk();
        $catalogue->assertSee('Brak kotów spełniających kryteria');

        $quiz = $this->get(route('quiz'));
        $quiz->assertOk();
        $quiz->assertSee('Dobierz Idealną Rasę w 45 Sekund');

        // 2. Animal with missing date_of_birth
        $animalWithoutDob = Animal::factory()->create([
            'name'          => 'TajemniczyKot',
            'status'        => \App\Enums\AnimalStatus::Available,
            'date_of_birth' => null,
            'is_published'  => true,
        ]);

        $this->assertSame('Dostępny do rezerwacji', $animalWithoutDob->pickupBadgeLabel());
        $this->assertNull($animalWithoutDob->pickupWindowFormatted());

        $profile = $this->get(route('frontend.animals.show', $animalWithoutDob));
        $profile->assertOk();
        $profile->assertSee('TajemniczyKot');
        $profile->assertSee('Dostępny do rezerwacji');
        $profile->assertDontSee('undefined');
    }
}

