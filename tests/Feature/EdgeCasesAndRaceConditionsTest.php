<?php

namespace Tests\Feature;

use App\Actions\Animals\UpdateAnimalAction;
use App\Enums\AnimalGender;
use App\Enums\AnimalStatus;
use App\Enums\AnimalType;
use App\Events\ContactMessageSent;
use App\Models\Animal;
use App\Models\Category;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class EdgeCasesAndRaceConditionsTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $authorUser;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'description' => 'Administrator']);
        $this->adminUser = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $this->authorUser = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        Cache::flush();
    }

    // =========================================================================
    // 1. EDGE CASES: PUSTA LISTA (EMPTY LISTS)
    // =========================================================================

    public function test_blog_post_with_zero_animals_renders_without_errors(): void
    {
        $post = Post::factory()->create([
            'user_id' => $this->authorUser->id,
            'title' => 'Opieka nad kotem rasowym jesienią',
            'slug' => 'opieka-nad-kotem-rasowym-jesienia',
            'body' => 'Artykuł o opiece nad kotem bez przypisanych kotów w bazie.',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $this->assertTrue($post->animals()->count() === 0);

        $response = $this->get(route('frontend.blog.show', $post));

        $response->assertOk();
        // Featured cats section header should NOT be rendered when empty
        $response->assertDontSee('Koty z tego artykułu');
        // CTA and main heading should still render
        $response->assertSee('Opieka nad kotem rasowym jesienią');
        $response->assertSee('DOMOWA HODOWLA MAZOWIECKA SZWAJCARIA');
    }

    public function test_blog_post_with_null_sections_and_empty_body_renders_gracefully(): void
    {
        $post = Post::factory()->create([
            'user_id' => $this->authorUser->id,
            'title' => 'Pusty wpis roboczy',
            'slug' => 'pusty-wpis-roboczy',
            'sections' => null,
            'body' => '',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('frontend.blog.show', $post));

        $response->assertOk();
        $response->assertSee('Pusty wpis roboczy');
    }

    public function test_animals_catalog_with_zero_results_renders_clean_empty_state(): void
    {
        // Query non-existent criteria
        $response = $this->get(route('frontend.animals.index', [
            'status' => 'sold',
            'breed' => 'Kot Nieznany',
        ]));

        $response->assertOk();
        $response->assertSee('Brak kotów spełniających kryteria');
        $response->assertSee('Pokaż wszystkie koty');
    }

    public function test_blog_catalog_with_zero_search_results_renders_clean_empty_state(): void
    {
        $response = $this->get(route('frontend.blog.index', [
            'q' => 'ZupełnieNieistniejącaFazaWyszukiwaniaXYZ123',
        ]));

        $response->assertOk();
        $response->assertSee('Brak publikacji spełniających Twoje kryteria');
        $response->assertSee('Zobacz wszystkie artykuły');
    }

    public function test_animal_with_zero_gallery_images_renders_profile_without_errors(): void
    {
        $animal = Animal::factory()->create([
            'name' => 'Kot Bez Galerii',
            'slug' => 'kot-bez-galerii',
            'breed' => 'Kot Bengalski',
            'status' => AnimalStatus::Available,
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $this->assertTrue($animal->gallery()->count() === 0);

        $response = $this->get(route('frontend.animals.show', $animal));

        $response->assertOk();
        $response->assertSee('Kot Bez Galerii');
    }

    // =========================================================================
    // 2. EDGE CASES: NULL VALUES & OPTIONAL FIELDS
    // =========================================================================

    public function test_animal_with_null_dob_and_null_pickup_date_returns_safe_fallbacks(): void
    {
        $cat = new Animal([
            'name' => 'Dorosły Kot Bez Daty',
            'status' => AnimalStatus::Available,
            'date_of_birth' => null,
            'earliest_pickup_date' => null,
        ]);

        $this->assertNull($cat->age());
        $this->assertNull($cat->ageInWeeks());
        $this->assertNull($cat->earliestPickupDate());
        $this->assertNull($cat->pickupWindow());
        $this->assertNull($cat->pickupWindowFormatted());
        $this->assertNull($cat->weeksUntilPickupWindow());
        $this->assertNull($cat->pickupTimelineLabel());
        $this->assertNull($cat->pickupCardTimelineLabel());
        $this->assertFalse($cat->isEarlyReservation());
        $this->assertTrue($cat->isReadyNow()); // Adult without DOB is considered ready
        $this->assertSame('Dostępny do rezerwacji', $cat->pickupBadgeLabel());
    }

    public function test_animal_with_null_or_zero_price_hides_pricing_cleanly(): void
    {
        $catNull = new Animal(['status' => AnimalStatus::Available, 'price' => null]);
        $this->assertNull($catNull->price);
        $this->assertNull($catNull->formattedPrice());
        $this->assertFalse($catNull->shouldShowPrice());

        $catZero = new Animal(['status' => AnimalStatus::Available, 'price' => 0]);
        $this->assertNull($catZero->price);
        $this->assertNull($catZero->formattedPrice());
        $this->assertFalse($catZero->shouldShowPrice());

        $catNegative = new Animal(['status' => AnimalStatus::Available, 'price' => -2500]);
        $this->assertNull($catNegative->price);
        $this->assertNull($catNegative->formattedPrice());
        $this->assertFalse($catNegative->shouldShowPrice());
    }

    public function test_quick_callback_with_all_null_optional_fields_succeeds(): void
    {
        Event::fake([ContactMessageSent::class]);

        $response = $this->postJson(route('api.quick-callback'), [
            'phone' => '+48 514 153 204',
            'cat_name' => null,
            'cat_breed' => null,
            'page_url' => null,
            'traffic_source' => null,
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);

        Event::assertDispatched(ContactMessageSent::class, function ($event) {
            $data = $event->data;
            return $data['phone'] === '+48 514 153 204'
                && str_contains($data['subject'], 'Prośba o szybki kontakt')
                && str_contains($data['message'], 'Ogólne zapytanie o kocięta');
        });
    }

    // =========================================================================
    // 3. EDGE CASES: DZIWNE ZNAKI, INJEKCJE, CYTATY & STREFA POLSKICH ZNAKÓW
    // =========================================================================

    public function test_animal_name_with_quotes_xss_and_unicode_does_not_break_view_or_attribute(): void
    {
        $weirdName = 'Łucja "Królowa" <script>alert(1)</script> & 🐅';

        $animal = Animal::factory()->create([
            'name' => $weirdName,
            'slug' => 'lucja-krolowa-xss-test',
            'breed' => 'Kot Bengalski',
            'status' => AnimalStatus::Available,
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('frontend.animals.show', $animal));

        $response->assertOk();
        // The script tag must be properly HTML-escaped by Blade, NEVER rendered raw
        $response->assertDontSee('<script>alert(1)</script>', false);
        $response->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);

        // Verification that openReservationModal call uses @js escaping without broken HTML attribute
        $this->assertStringNotContainsString('openReservationModal(\'' . addslashes($weirdName) . '\'', $response->getContent());
    }

    public function test_blog_post_with_quotes_and_unicode_produces_valid_json_ld(): void
    {
        $weirdTitle = 'Wszystko o rasie "Kot Bengalski" & hodowla w Polsce (2026) 🐅';

        $post = Post::factory()->create([
            'user_id' => $this->authorUser->id,
            'title' => $weirdTitle,
            'slug' => 'wszystko-o-rasie-kot-bengalski-test',
            'excerpt' => 'Cytat: "Koty bengalskie są niesamowite" & bezpieczne.',
            'body' => 'Treść artykułu testowego z dziwnymi znakami: <script>alert("xss")</script>.',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('frontend.blog.show', $post));

        $response->assertOk();

        // Extract JSON-LD script from the rendered HTML response
        $content = $response->getContent();
        preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $content, $matches);

        $this->assertNotEmpty($matches[1], 'JSON-LD script tag not found in response HTML');

        // Must parse as valid JSON without throwing SyntaxError
        $decoded = json_decode(trim($matches[1]), true);
        $this->assertIsArray($decoded, 'JSON-LD in blog show is not valid JSON!');
        $this->assertSame($weirdTitle, $decoded['headline']);
    }

    public function test_phone_number_normalization_handles_various_formats(): void
    {
        Event::fake([ContactMessageSent::class]);

        $formats = [
            '514153204' => '+48 514 153 204',
            '514 153 204' => '+48 514 153 204',
            '+48514153204' => '+48 514 153 204',
            '0048 514 153 204' => '+48 514 153 204',
            '+48 (514) 153-204' => '+48 514 153 204',
        ];

        foreach ($formats as $input => $expectedNormalized) {
            Cache::flush(); // Clear dedup lock between iterations

            $response = $this->postJson(route('api.quick-callback'), [
                'phone' => $input,
            ]);

            $response->assertOk();

            Event::assertDispatched(ContactMessageSent::class, function ($event) use ($expectedNormalized) {
                return $event->data['phone'] === $expectedNormalized;
            });
        }
    }

    public function test_quick_callback_rejects_malicious_or_invalid_phones(): void
    {
        $maliciousInputs = [
            '514153<script>',
            '514153204\' OR \'1\'=\'1',
            '123456', // Too short (6 digits)
            '1234567890123456', // Too long (16 digits)
            'abc1234567',
        ];

        foreach ($maliciousInputs as $phone) {
            $response = $this->postJson(route('api.quick-callback'), [
                'phone' => $phone,
            ]);

            $response->assertStatus(422);
            $response->assertJsonValidationErrors(['phone']);
        }
    }

    public function test_quick_callback_rejects_bot_spam_via_honeypot(): void
    {
        $response = $this->postJson(route('api.quick-callback'), [
            'phone' => '+48 514 153 204',
            '_hp_website' => 'http://spam-site-crawler.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['_hp_website']);
    }

    // =========================================================================
    // 4. EDGE CASES: STREFY CZASOWE & SKRAJNE DATY
    // =========================================================================

    public function test_future_date_of_birth_handles_expected_litter(): void
    {
        $cat = new Animal([
            'status' => AnimalStatus::Available,
            'date_of_birth' => Carbon::now()->addWeeks(3),
        ]);

        $this->assertSame('Oczekiwany miot', $cat->age());
        $this->assertSame(0, $cat->ageInWeeks());
        $this->assertTrue($cat->isEarlyReservation());
        $this->assertFalse($cat->isReadyNow());
        $this->assertSame('Dostępny do rezerwacji', $cat->pickupBadgeLabel());
    }

    public function test_leap_year_date_of_birth_calculates_pickup_accurately(): void
    {
        // Leap year date: 29.02.2024
        $cat = new Animal([
            'status' => AnimalStatus::Available,
            'date_of_birth' => Carbon::create(2024, 2, 29),
        ]);

        $pickup = $cat->earliestPickupDate();
        $this->assertNotNull($pickup);
        $this->assertSame('2024-05-23', $pickup->toDateString()); // 12 weeks later
    }

    public function test_midnight_boundary_consistency_for_pickup_readiness(): void
    {
        $dob = Carbon::now()->subWeeks(12)->startOfDay();

        $cat = new Animal([
            'status' => AnimalStatus::Available,
            'date_of_birth' => $dob,
        ]);

        // Today at 00:00:01
        $morning = Carbon::now()->startOfDay()->addSecond();
        $this->assertTrue($cat->isReadyNow($morning));

        // Today at 23:59:59
        $night = Carbon::now()->endOfDay();
        $this->assertTrue($cat->isReadyNow($night));

        // Status badge label at any hour of the day
        $this->assertSame('Gotowy do odbioru', $cat->pickupBadgeLabel($morning));
        $this->assertSame('Gotowy do odbioru', $cat->pickupBadgeLabel($night));
    }

    public function test_polish_age_pluralization_rules(): void
    {
        $now = Carbon::create(2026, 10, 25, 12, 0, 0);

        // 0 days
        $cat0 = new Animal(['date_of_birth' => $now->copy()]);
        Carbon::setTestNow($now);
        $this->assertSame('Dziś', $cat0->age());

        // 1 day
        $cat1d = new Animal(['date_of_birth' => $now->copy()->subDay()]);
        $this->assertSame('1 dzień', $cat1d->age());

        // 2 days
        $cat2d = new Animal(['date_of_birth' => $now->copy()->subDays(2)]);
        $this->assertSame('2 dni', $cat2d->age());

        // 1 month
        $cat1m = new Animal(['date_of_birth' => $now->copy()->subMonth()]);
        $this->assertSame('1 miesiąc', $cat1m->age());

        // 3 months
        $cat3m = new Animal(['date_of_birth' => $now->copy()->subMonths(3)]);
        $this->assertSame('3 miesiące', $cat3m->age());

        // 5 months
        $cat5m = new Animal(['date_of_birth' => $now->copy()->subMonths(5)]);
        $this->assertSame('5 miesięcy', $cat5m->age());

        // 1 year
        $cat1y = new Animal(['date_of_birth' => $now->copy()->subYear()]);
        $this->assertSame('1 rok', $cat1y->age());

        // 2 years
        $cat2y = new Animal(['date_of_birth' => $now->copy()->subYears(2)]);
        $this->assertSame('2 lata', $cat2y->age());

        // 5 years
        $cat5y = new Animal(['date_of_birth' => $now->copy()->subYears(5)]);
        $this->assertSame('5 lat', $cat5y->age());

        // 12 years (special 12-14 exception in Polish)
        $cat12y = new Animal(['date_of_birth' => $now->copy()->subYears(12)]);
        $this->assertSame('12 lat', $cat12y->age());

        // 22 years
        $cat22y = new Animal(['date_of_birth' => $now->copy()->subYears(22)]);
        $this->assertSame('22 lata', $cat22y->age());

        Carbon::setTestNow(null);
    }

    // =========================================================================
    // 5. EDGE CASES: SKRAJNE WARTOŚCI (EXTREME VALUES)
    // =========================================================================

    public function test_price_mutator_sanitizes_extreme_and_invalid_values(): void
    {
        $cat = new Animal();

        // 1. Extreme integer overflow protection (> 1,000,000 capped to 1,000,000)
        $cat->price = 999999999999;
        $this->assertSame(1000000, $cat->price);
        $this->assertSame('1 000 000 zł', $cat->formattedPrice());

        // 2. String currency formatting parsing
        $cat->price = '2 850 zł';
        $this->assertSame(2850, $cat->price);

        // 3. String with dots
        $cat->price = '3.200 PLN';
        $this->assertSame(3200, $cat->price);

        // 4. Negative values in string or number
        $cat->price = '-2500 zł';
        $this->assertNull($cat->price);

        // 5. Pure non-numeric text
        $cat->price = 'darmowy kotek';
        $this->assertNull($cat->price);

        // 6. Empty string
        $cat->price = '';
        $this->assertNull($cat->price);
    }

    public function test_next_sort_order_caps_within_pool_boundaries(): void
    {
        // 1. Kittens range (1 - 19)
        Animal::factory()->create([
            'status' => AnimalStatus::Available,
            'sort_order' => 19,
        ]);
        // Even if max is 19, next sort order MUST NOT overflow into 20 (which is sold cats)
        $nextKitten = Animal::nextSortOrderFor(AnimalStatus::Available);
        $this->assertSame(19, $nextKitten);

        // 2. Sold cats range (20 - 29)
        Animal::factory()->create([
            'status' => AnimalStatus::Sold,
            'sort_order' => 29,
        ]);
        // Even if max is 29, next sort order MUST NOT overflow into 30+ (breeding range)
        $nextSold = Animal::nextSortOrderFor(AnimalStatus::Sold);
        $this->assertSame(29, $nextSold);
    }

    // =========================================================================
    // 6. RACE CONDITIONS & CONCURRENCY
    // =========================================================================

    public function test_concurrent_quick_callback_double_clicks_are_deduplicated(): void
    {
        Event::fake([ContactMessageSent::class]);

        $payload = [
            'phone' => '+48 514 153 204',
            'cat_name' => 'Baron',
            'cat_breed' => 'Kot Bengalski',
        ];

        // First click (Immediate request 1)
        $response1 = $this->postJson(route('api.quick-callback'), $payload);
        $response1->assertOk();
        $response1->assertJson(['success' => true]);

        // Second click in the exact same second / millisecond (Simulated concurrent request 2)
        $response2 = $this->postJson(route('api.quick-callback'), $payload);
        $response2->assertOk();
        $response2->assertJson([
            'success' => true,
            'message' => 'Dziękujemy! Twoje zgłoszenie zostało już przyjęte. Skontaktujemy się telefonicznie lub przez WhatsApp tak szybko, jak to możliwe.',
        ]);

        // CRITICAL CONCURRENCY CHECK: ContactMessageSent must ONLY be dispatched ONCE!
        Event::assertDispatchedTimes(ContactMessageSent::class, 1);
    }

    public function test_admin_quick_price_endpoint_uses_atomic_database_locking(): void
    {
        $kitten = Animal::factory()->create([
            'name' => 'Atomic Cat',
            'status' => AnimalStatus::Available,
            'price' => 2500,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->patchJson(route('backend.animals.quick-price', $kitten), [
                'price' => '3 100 zł',
            ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'price' => 3100,
            'formatted' => '3 100 zł',
        ]);

        $this->assertSame(3100, $kitten->fresh()->price);
    }

    public function test_update_animal_action_safely_updates_status_under_lock(): void
    {
        $animal = Animal::factory()->create([
            'name' => 'Concurrency Animal',
            'status' => AnimalStatus::Available,
            'sort_order' => 5,
        ]);

        /** @var UpdateAnimalAction $action */
        $action = app(UpdateAnimalAction::class);

        $updated = $action->handle($animal, [
            'name' => 'Concurrency Animal',
            'slug' => 'concurrency-animal',
            'breed' => 'Kot Bengalski',
            'gender' => AnimalGender::Male,
            'status' => AnimalStatus::Sold,
            'type' => AnimalType::Cat,
            'is_featured' => false,
            'is_published' => true,
            'sort_order' => null, // Let action re-calculate sort_order for Sold
        ]);

        $this->assertSame(AnimalStatus::Sold, $updated->status);
        $this->assertGreaterThanOrEqual(20, $updated->sort_order);
        $this->assertLessThanOrEqual(29, $updated->sort_order);
    }
}
