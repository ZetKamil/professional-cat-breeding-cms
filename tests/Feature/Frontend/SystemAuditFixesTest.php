<?php

declare(strict_types=1);

namespace Tests\Feature\Frontend;

use App\Enums\AnimalGender;
use App\Enums\AnimalStatus;
use App\Enums\AnimalType;
use App\Models\Animal;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemAuditFixesTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_cat_with_null_published_at_is_visible_and_returns_200_not_404(): void
    {
        // Simulate cat like Alma: is_published = true, published_at = null
        $cat = Animal::create([
            'name' => 'Alma Test',
            'slug' => 'alma-test',
            'breed' => 'Kot Brytyjski',
            'gender' => AnimalGender::Female,
            'status' => AnimalStatus::Available,
            'type' => AnimalType::Cat,
            'is_featured' => true,
            'is_published' => true,
            'published_at' => null,
            'sort_order' => 1,
        ]);

        // 1. Verify in scopePublished
        $this->assertTrue(Animal::published()->where('slug', 'alma-test')->exists());

        // 2. Verify catalog includes the cat
        $response = $this->get(route('frontend.animals.index'));
        $response->assertOk();
        $response->assertSee('Alma Test');

        // 3. Verify single profile view returns 200 (not 404!)
        $profileResponse = $this->get(route('frontend.animals.show', $cat));
        $profileResponse->assertOk();
        $profileResponse->assertSee('Alma Test');
    }

    public function test_scheduled_future_cat_is_hidden_and_returns_404(): void
    {
        $futureCat = Animal::create([
            'name' => 'Future Cat',
            'slug' => 'future-cat',
            'breed' => 'Kot Bengalski',
            'gender' => AnimalGender::Male,
            'status' => AnimalStatus::Available,
            'type' => AnimalType::Cat,
            'is_featured' => false,
            'is_published' => true,
            'published_at' => now()->addDays(5),
            'sort_order' => 2,
        ]);

        $this->assertFalse(Animal::published()->where('slug', 'future-cat')->exists());

        $profileResponse = $this->get(route('frontend.animals.show', $futureCat));
        $profileResponse->assertNotFound();
    }

    public function test_retired_status_cats_are_included_in_published_scope_and_filter(): void
    {
        $retiredCat = Animal::create([
            'name' => 'Senior Cat',
            'slug' => 'senior-cat',
            'breed' => 'Kot Syjamski',
            'gender' => AnimalGender::Female,
            'status' => AnimalStatus::Retired,
            'type' => AnimalType::Cat,
            'is_featured' => false,
            'is_published' => true,
            'published_at' => now()->subMonths(1),
            'sort_order' => 30,
        ]);

        $this->assertTrue(Animal::published()->where('slug', 'senior-cat')->exists());

        $response = $this->get(route('frontend.animals.index', ['status' => 'retired']));
        $response->assertOk();
        $response->assertSee('Senior Cat');
    }

    public function test_services_config_defines_meta_pixel_key(): void
    {
        $servicesConfig = config('services.meta');
        $this->assertIsArray($servicesConfig);
        $this->assertArrayHasKey('pixel_id', $servicesConfig);

        $fbConfig = config('services.facebook');
        $this->assertIsArray($fbConfig);
        $this->assertArrayHasKey('pixel_id', $fbConfig);
    }

    public function test_mail_config_defines_contact_recipient_and_listener_uses_it(): void
    {
        $recipient = config('mail.contact_recipient');
        $this->assertNotEmpty($recipient);

        $listenerContent = file_get_contents(app_path('Listeners/SendContactMessageMail.php'));
        $this->assertStringContainsString("config('mail.contact_recipient'", $listenerContent);
        $this->assertStringNotContainsString('env(', $listenerContent);
    }

    public function test_animal_profile_includes_dynamic_utm_attribution_and_ga4_tracking(): void
    {
        $cat = Animal::create([
            'name' => 'Baron Test',
            'slug' => 'baron-test',
            'breed' => 'Kot Bengalski',
            'gender' => AnimalGender::Male,
            'status' => AnimalStatus::Available,
            'type' => AnimalType::Cat,
            'is_featured' => true,
            'is_published' => true,
            'published_at' => now(),
            'sort_order' => 1,
        ]);

        $response = $this->get(route('frontend.animals.show', $cat));
        $response->assertOk();

        // Messenger & WhatsApp tracking
        $response->assertSee("trackFunnelStep('contact_cta_click', { channel: 'messenger', origin: 'animal_profile', cat_name: 'Baron Test' }", false);
        $response->assertSee("trackFunnelStep('contact_cta_click', { channel: 'whatsapp', origin: 'animal_profile', cat_name: 'Baron Test' }", false);
        $response->assertDontSee('[Źródło:', false);
    }

    public function test_blog_post_includes_dynamic_utm_attribution_and_ga4_tracking(): void
    {
        $author = User::factory()->create();
        $post = Post::updateOrCreate(
            ['slug' => 'test-artykul-audyt'],
            [
                'title' => 'Testowy artykuł z audytu',
                'excerpt' => 'Krótki wstęp testowy',
                'body' => 'Treść testowa artykułu...',
                'is_published' => true,
                'published_at' => now()->subDay(),
                'author_id' => $author->id,
            ]
        );

        $response = $this->get(route('frontend.blog.show', $post));
        $response->assertOk();

        $response->assertSee("trackFunnelStep('contact_cta_click', { channel: 'messenger', origin: 'blog_post'", false);
        $response->assertSee("trackFunnelStep('contact_cta_click', { channel: 'whatsapp', origin: 'blog_post'", false);
        $response->assertDontSee('[Źródło:', false);
    }

    public function test_contact_page_includes_dynamic_utm_attribution_and_ga4_tracking(): void
    {
        $response = $this->get(route('contact'));
        $response->assertOk();

        $response->assertSee("trackFunnelStep('contact_cta_click', { channel: 'whatsapp', origin: 'contact_page' }", false);
        $response->assertSee("trackFunnelStep('contact_cta_click', { channel: 'messenger', origin: 'contact_page' }", false);
        $response->assertDontSee('[Źródło:', false);
    }

    public function test_pagination_is_located_at_bottom_only_in_animals_catalog(): void
    {
        $viewContent = file_get_contents(resource_path('views/frontend/animals/index.blade.php'));
        $this->assertStringNotContainsString('animals-catalog__pagination--top', $viewContent);
        $this->assertStringContainsString('animals-catalog__pagination', $viewContent);
    }

    public function test_shell_renders_meta_pixel_with_fallback_identifiers(): void
    {
        config([
            'services.meta.pixel_id' => null,
            'services.facebook.pixel_id' => '99887766554433',
        ]);

        $response = $this->get(route('home'));
        $response->assertOk();
        $response->assertSee("fbq('init', '99887766554433')", false);
        $response->assertSee('connect.facebook.net/en_US/fbevents.js', false);
    }

    public function test_offspring_with_null_published_at_is_visible_in_parent_profile(): void
    {
        $mother = Animal::create([
            'name' => 'Matka Bella',
            'slug' => 'matka-bella',
            'breed' => 'Kot Brytyjski',
            'gender' => AnimalGender::Female,
            'status' => AnimalStatus::Breeding,
            'type' => AnimalType::Cat,
            'is_featured' => false,
            'is_published' => true,
            'published_at' => now()->subYear(),
            'sort_order' => 1,
        ]);

        $childVisible = Animal::create([
            'name' => 'Kotek Widoczny',
            'slug' => 'kotek-widoczny',
            'breed' => 'Kot Brytyjski',
            'gender' => AnimalGender::Male,
            'status' => AnimalStatus::Available,
            'type' => AnimalType::Cat,
            'mother_id' => $mother->id,
            'is_featured' => false,
            'is_published' => true,
            'published_at' => null, // null published_at must not hide child!
            'sort_order' => 1,
        ]);

        $childFuture = Animal::create([
            'name' => 'Kotek W Przyszlosci',
            'slug' => 'kotek-w-przyszlosci',
            'breed' => 'Kot Brytyjski',
            'gender' => AnimalGender::Female,
            'status' => AnimalStatus::Available,
            'type' => AnimalType::Cat,
            'mother_id' => $mother->id,
            'is_featured' => false,
            'is_published' => true,
            'published_at' => now()->addDays(10), // Future scheduled must remain hidden
            'sort_order' => 2,
        ]);

        $response = $this->get(route('frontend.animals.show', $mother));
        $response->assertOk();
        $response->assertSee('Kotek Widoczny');
        $response->assertDontSee('Kotek W Przyszlosci');
    }

    public function test_breeding_cat_profile_displays_role_and_planned_litters_cta_not_pickup_or_reservation(): void
    {
        $stud = Animal::create([
            'name' => 'Cezar Reproduktor',
            'slug' => 'cezar-reproduktor',
            'breed' => 'Kot Bengalski',
            'gender' => AnimalGender::Male,
            'status' => AnimalStatus::Breeding,
            'type' => AnimalType::Cat,
            'is_featured' => true,
            'is_published' => true,
            'published_at' => now()->subMonths(6),
            'sort_order' => 1,
        ]);

        $response = $this->get(route('frontend.animals.show', $stud));
        $response->assertOk();
        $response->assertSee('Rola w hodowli');
        $response->assertSee('Reproduktor');
        $response->assertDontSee('<span class="animal-spec-card__label">Odbiór</span>', false);
        $response->assertSee('Zapytaj o planowane mioty');
        $response->assertDontSee('Umów wideo-wizytę / Zarezerwuj');
        $response->assertDontSee("window.openReservationModal('Cezar Reproduktor'", false);
    }

    public function test_sold_cat_profile_hides_live_video_callout_and_directs_to_available_kittens(): void
    {
        $soldCat = Animal::create([
            'name' => 'Felicjan Sprzedany',
            'slug' => 'felicjan-sprzedany',
            'breed' => 'Kot Syjamski',
            'gender' => AnimalGender::Male,
            'status' => AnimalStatus::Sold,
            'type' => AnimalType::Cat,
            'is_featured' => false,
            'is_published' => true,
            'published_at' => now()->subMonths(3),
            'sort_order' => 25,
        ]);

        $response = $this->get(route('frontend.animals.show', $soldCat));
        $response->assertOk();
        $response->assertSee('Kotek Felicjan Sprzedany zamieszkał już u nowej rodziny');
        $response->assertSee('Zobacz dostępne kocięta');
        $response->assertDontSee('Umów wideo-wizytę na żywo');
        $response->assertDontSee("window.openReservationModal('Felicjan Sprzedany'", false);
    }

    public function test_updating_animal_from_sold_to_available_resets_sort_order_to_available_pool(): void
    {
        $cat = Animal::create([
            'name' => 'Puszek Powrot',
            'slug' => 'puszek-powrot',
            'breed' => 'Kot Brytyjski',
            'gender' => AnimalGender::Male,
            'status' => AnimalStatus::Sold,
            'type' => AnimalType::Cat,
            'is_featured' => false,
            'is_published' => true,
            'published_at' => now()->subMonths(1),
            'sort_order' => 25,
        ]);

        $action = app(\App\Actions\Animals\UpdateAnimalAction::class);
        $updated = $action->handle($cat, [
            'name' => 'Puszek Powrot',
            'slug' => 'puszek-powrot',
            'breed' => 'Kot Brytyjski',
            'gender' => AnimalGender::Male->value,
            'type' => AnimalType::Cat->value,
            'status' => AnimalStatus::Available->value,
            'is_featured' => false,
            'is_published' => true,
        ]);

        $this->assertSame(AnimalStatus::Available, $updated->status);
        $this->assertLessThan(20, $updated->sort_order, 'Sort order must reset back to available pool (1-19)');
    }
}
