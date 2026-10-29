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
}
