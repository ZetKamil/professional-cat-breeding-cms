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
        $this->assertSame('Wczesna rezerwacja • za 8 tyg.', $youngKitten->pickupBadgeLabel($now));
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
        $this->assertSame('Gotowy do odbioru (12–14 tyg.)', $boundary12->pickupBadgeLabel($now));
        $this->assertSame('gold', $boundary12->pickupBadgeVariant($now));

        // 3. Kitten at 13 weeks old (inside 12–14 week window)
        $kitten13 = Animal::factory()->create([
            'status' => \App\Enums\AnimalStatus::Available,
            'date_of_birth' => $now->copy()->subWeeks(13),
        ]);

        $this->assertSame(13, $kitten13->ageInWeeks($now));
        $this->assertTrue($kitten13->isInPickupWindow($now));
        $this->assertTrue($kitten13->isReadyNow($now));
        $this->assertSame('Gotowy do odbioru (12–14 tyg.)', $kitten13->pickupBadgeLabel($now));

        // 4. Kitten at boundary: exactly 14 weeks old (upper limit of pickup window)
        $boundary14 = Animal::factory()->create([
            'status' => \App\Enums\AnimalStatus::Available,
            'date_of_birth' => $now->copy()->subWeeks(14),
        ]);

        $this->assertSame(14, $boundary14->ageInWeeks($now));
        $this->assertTrue($boundary14->isInPickupWindow($now));
        $this->assertFalse($boundary14->isOdchowany($now));
        $this->assertSame('Gotowy do odbioru (12–14 tyg.)', $boundary14->pickupBadgeLabel($now));

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
        $this->assertSame('Gotowy do odbioru • Odchowany', $olderKitten->pickupBadgeLabel($now));
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
        $earlyProfile->assertSee('Wczesna rezerwacja • za 7 tyg.');
        $earlyProfile->assertSee('Okno odbioru (12–14 tyg.)');
        $earlyProfile->assertSee($earlyKitten->pickupWindowFormatted());
        $earlyProfile->assertSee('Zasada odbioru:');
        $earlyProfile->assertSee('Kocięta mogą wyjeżdżać do nowych domów w wieku 12–14 tygodni, po potwierdzeniu gotowości konkretnego kociaka.');

        // Verify Ready cat profile: show page displays 12-14 weeks ready badge
        $readyProfile = $this->get(route('frontend.animals.show', $readyKitten));
        $readyProfile->assertOk();
        $readyProfile->assertSee('Gotowy do odbioru (12–14 tyg.)');
        $readyProfile->assertSee('Okno odbioru (12–14 tyg.)');
        $readyProfile->assertSee($readyKitten->pickupWindowFormatted());

        // Verify Homepage cards display matching badges
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk();
        $homeResponse->assertSee('Wczesna rezerwacja • za 7 tyg.');
        $homeResponse->assertSee('Gotowy do odbioru (12–14 tyg.)');

        \Illuminate\Support\Carbon::setTestNow();
    }
}

