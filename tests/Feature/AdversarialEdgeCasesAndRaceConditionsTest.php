<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\AnimalGender;
use App\Enums\AnimalStatus;
use App\Enums\AnimalType;
use App\Events\ContactMessageSent;
use App\Models\Animal;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AdversarialEdgeCasesAndRaceConditionsTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'description' => 'Administrator']);
        $this->adminUser = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        Cache::flush();
    }

    // =========================================================================
    // 1. ADVERSARIAL EDGE CASES: PRICES & FELINOLOGICAL PRESENTATION
    // =========================================================================

    public function test_price_mutation_with_comma_and_dot_cents_handles_fractions_accurately(): void
    {
        $cat = new Animal(['status' => AnimalStatus::Available]);

        // Input with Polish comma grosze: '2 700,50 zł' -> must NOT become 270 050!
        $cat->price = '2 700,50 zł';
        $this->assertSame(2700, $cat->price);
        $this->assertSame('2 700 zł', $cat->formattedPrice());

        // Input with dot cents: '3000.00 PLN' -> must NOT become 300 000!
        $cat->price = '3000.00 PLN';
        $this->assertSame(3000, $cat->price);

        // Input with zero decimals: '2500,00'
        $cat->price = '2500,00';
        $this->assertSame(2500, $cat->price);
    }

    public function test_price_mutation_extreme_overflow_capped_without_pdo_exception(): void
    {
        $cat = new Animal(['status' => AnimalStatus::Available]);

        // Huge integer string overflow that would break 32-bit/64-bit SQL integer types
        $cat->price = '999999999999999999999999999999999999999999999999999';
        $this->assertSame(1000000, $cat->price);
        $this->assertSame('1 000 000 zł', $cat->formattedPrice());
    }

    public function test_status_transition_from_available_to_sold_immediately_hides_pricing_and_badges(): void
    {
        $kitten = Animal::factory()->create([
            'name' => 'Baron Transyt',
            'breed' => 'Kot Bengalski',
            'status' => AnimalStatus::Available,
            'is_published' => true,
            'price' => 2700,
            'slug' => 'baron-transyt',
        ]);

        $this->assertTrue($kitten->shouldShowPrice());

        // 1. Initial available state has price
        $response1 = $this->get(route('frontend.animals.show', $kitten));
        $response1->assertOk();
        $response1->assertSee('KOCIAK W OPCJI „NA KOLANKA”');
        $response1->assertSee('2 700 zł');

        // 2. Immediate transition to Sold
        $kitten->update(['status' => AnimalStatus::Sold]);
        $kitten->refresh();

        $this->assertFalse($kitten->shouldShowPrice());

        // 3. Public views must strictly strip pricing immediately
        $response2 = $this->get(route('frontend.animals.show', $kitten));
        $response2->assertOk();
        $response2->assertDontSee('KOCIAK W OPCJI „NA KOLANKA”');
        $response2->assertDontSee('animal-profile__price-box', false);

        $catalogResponse = $this->get(route('frontend.animals.index'));
        $catalogResponse->assertOk();
        $catalogResponse->assertDontSee('Na kolanka: 2 700 zł');
    }

    public function test_breeding_cat_with_accidental_price_in_database_never_exposes_price_publicly(): void
    {
        $adultCat = Animal::factory()->create([
            'name' => 'Atos Reproduktor Chroniony',
            'breed' => 'Kot Bengalski',
            'status' => AnimalStatus::Breeding,
            'is_published' => true,
            'price' => 5000, // Accidental price in DB
            'slug' => 'atos-chroniony',
        ]);

        $this->assertFalse($adultCat->shouldShowPrice());

        // Catalog test: the 5 000 zł price must NEVER be displayed
        $indexResponse = $this->get(route('frontend.animals.index'));
        $indexResponse->assertOk();
        $indexResponse->assertDontSee('5 000 zł');
        $indexResponse->assertDontSee('Na kolanka: 5 000 zł');

        // Profile test: price box and value must strictly be absent
        $profileResponse = $this->get(route('frontend.animals.show', $adultCat));
        $profileResponse->assertOk();
        $profileResponse->assertDontSee('5 000 zł');
        $profileResponse->assertDontSee('KOCIAK W OPCJI „NA KOLANKA”');
        $profileResponse->assertDontSee('animal-profile__price-box', false);
    }

    // =========================================================================
    // 2. ADVERSARIAL EDGE CASES: BLOG, EXCERPTS & HEADINGS
    // =========================================================================

    public function test_blog_post_with_only_headings_does_not_output_headings_in_card_excerpt(): void
    {
        $post = new Post([
            'title' => 'Tylko Nagłówki',
            'body' => "# Nagłówek Pierwszy\n\n## Podtytuł Drugi\n\n### Sekcja Trzecia",
            'excerpt' => null,
            'meta_description' => null,
        ]);

        $excerpt = $post->cardExcerpt(120);

        // Heading lines starting with # must be totally omitted, never left as raw text
        $this->assertStringNotContainsString('#', $excerpt);
        $this->assertSame('', $excerpt);
    }

    public function test_blog_post_with_fenced_code_blocks_and_markdown_tables_strips_them_from_excerpt(): void
    {
        $body = "```json\n{\"sensitive_debug\": true}\n```\n\n| Tabela | Kolumna |\n|---|---|\n| Dane | Dane |\n\nPrawdziwa treść wstępu do artykułu o opiece nad kotem.";

        $post = new Post([
            'title' => 'Wpis z Kodem i Tabelami',
            'body' => $body,
            'excerpt' => null,
            'meta_description' => null,
        ]);

        $excerpt = $post->cardExcerpt(120);

        $this->assertStringNotContainsString('sensitive_debug', $excerpt);
        $this->assertStringNotContainsString('Tabela', $excerpt);
        $this->assertStringContainsString('Prawdziwa treść wstępu do artykułu', $excerpt);
    }

    public function test_blog_hero_never_outputs_lead_excerpt_even_with_custom_excerpt_in_db(): void
    {
        $post = Post::factory()->create([
            'user_id' => $this->adminUser->id,
            'title' => 'Artykuł z Długą Zajawką Testową',
            'slug' => 'artykul-z-dluga-zajawka-testowa',
            'meta_description' => 'Czysty opis meta SEO artykułu',
            'excerpt' => 'Ten unikalny teaser nigdy nie powinien pojawić się pod H1 w nagłówku hero!',
            'body' => 'Główna treść artykułu...',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('frontend.blog.show', $post));
        $response->assertOk();

        // The hero cover must strictly render only title and author bar, NEVER repeating the excerpt under H1
        $response->assertDontSee('article-cover-hero__lead', false);

        // Verify within article-cover-hero DOM section that excerpt is not present
        $html = $response->getContent();
        preg_match('/<section class="article-cover-hero".*?<\/section>/s', $html, $heroMatches);
        $this->assertNotEmpty($heroMatches[0]);
        $this->assertStringNotContainsString('Ten unikalny teaser', $heroMatches[0]);
    }

    public function test_timezones_scheduled_blog_posts_hidden_until_exact_published_at_utc(): void
    {
        Carbon::setTestNow('2026-10-27 12:00:00');

        $scheduledPost = Post::factory()->create([
            'user_id' => $this->adminUser->id,
            'title' => 'Zaplanowany Post Przyszły',
            'slug' => 'zaplanowany-post-przyszly',
            'is_published' => true,
            'published_at' => Carbon::parse('2026-10-27 12:05:00'), // 5 minutes in future
        ]);

        // 1. In the future -> Guest gets 404
        $response1 = $this->get(route('frontend.blog.show', $scheduledPost));
        $response1->assertNotFound();

        // 2. Fast forward time 6 minutes -> Post is now live
        Carbon::setTestNow('2026-10-27 12:06:00');

        $response2 = $this->get(route('frontend.blog.show', $scheduledPost));
        $response2->assertOk();
        $response2->assertSee('Zaplanowany Post Przyszły');

        Carbon::setTestNow(); // Reset test clock
    }

    // =========================================================================
    // 3. ADVERSARIAL EDGE CASES: PHRASING INTEGRITY ("CAGE-FREE")
    // =========================================================================

    public function test_total_absence_of_cage_words_across_entire_public_catalog_and_content(): void
    {
        $routes = [
            route('home'),
            route('frontend.animals.index'),
            route('cattery'),
            route('quiz'),
            route('seo.warszawa'),
            route('contact'),
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertOk();

            $content = $response->getContent();

            // Regex for all Polish declensions of klatka (klatek, klatkach, klatki, klatkowanie etc.)
            $this->assertDoesNotMatchRegularExpression(
                '/\bklatk[a-ząęółśżźćń]*\b/ui',
                $content,
                "Route [{$route}] unexpectedly contains cage-related word!"
            );
        }
    }

    // =========================================================================
    // 4. RACE CONDITIONS & CONCURRENCY
    // =========================================================================

    public function test_race_condition_quick_callback_atomic_lock_prevents_duplicate_notifications(): void
    {
        Event::fake([ContactMessageSent::class]);

        $payload = [
            'phone' => '+48 514 153 204',
            'cat_name' => 'Baron',
            'cat_breed' => 'Kot Bengalski',
        ];

        // First click
        $res1 = $this->postJson(route('api.quick-callback'), $payload);
        $res1->assertOk();
        $res1->assertJson(['success' => true]);

        // Second click in the exact same millisecond
        $res2 = $this->postJson(route('api.quick-callback'), $payload);
        $res2->assertOk();
        $res2->assertJson([
            'success' => true,
            'message' => 'Dziękujemy! Twoje zgłoszenie zostało już przyjęte. Skontaktujemy się telefonicznie lub przez WhatsApp tak szybko, jak to możliwe.',
        ]);

        // Deduplication guarantee: Event dispatched strictly ONCE
        Event::assertDispatchedTimes(ContactMessageSent::class, 1);
    }

    public function test_race_condition_concurrent_price_updates_maintain_database_integrity(): void
    {
        $kitten = Animal::factory()->create([
            'name' => 'Kociak Race',
            'status' => AnimalStatus::Available,
            'price' => 2500,
        ]);

        // Simulate Admin 1 updating price
        $res1 = $this->actingAs($this->adminUser)
            ->patchJson(route('backend.animals.quick-price', $kitten), [
                'price' => '2 800 zł',
            ]);
        $res1->assertOk();

        // Simulate Admin 2 updating price immediately after
        $res2 = $this->actingAs($this->adminUser)
            ->patchJson(route('backend.animals.quick-price', $kitten), [
                'price' => '2 900 zł',
            ]);
        $res2->assertOk();

        $this->assertSame(2900, $kitten->fresh()->price);
        $this->assertSame('2 900 zł', $kitten->fresh()->formattedPrice());
    }

    public function test_quiz_results_when_all_cats_in_database_are_sold_or_breeding(): void
    {
        // Setup only Sold and Breeding cats in the database
        Animal::factory()->create([
            'name' => 'Bella Matka',
            'breed' => 'Kot Bengalski',
            'status' => AnimalStatus::Breeding,
            'is_published' => true,
        ]);

        Animal::factory()->create([
            'name' => 'Carlos Sprzedany',
            'breed' => 'Kot Bengalski',
            'status' => AnimalStatus::Sold,
            'is_published' => true,
        ]);

        $response = $this->get(route('quiz'));
        $response->assertOk();

        // The quiz page must render safely with HTTP 200 without throwing errors
        $response->assertSee('Dobierz Idealną Rasę');

        // Strictly verify that sold or breeding cats are NEVER rendered in available cat items
        $response->assertDontSee('Carlos Sprzedany');
        $response->assertDontSee('Bella Matka');
    }
}
