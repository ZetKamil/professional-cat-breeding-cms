<?php

declare(strict_types=1);

namespace Tests\Feature\Frontend;

use App\Models\Animal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnimalTest extends TestCase
{
    use RefreshDatabase;

    public function test_animal_catalog_page_can_be_rendered(): void
    {
        Animal::factory()->create([
            'name' => 'Luna',
            'breed' => 'Kot Bengalski',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/koty');

        $response->assertOk();
        $response->assertSee('Kocięta i Koty z Rodowodem');
        $response->assertSee('Nasze Koty');
        $response->assertSee('Kot Bengalski');
        $response->assertSee('Kot Brytyjski');
        $response->assertSee('Kot Syjamski');
        $response->assertSee('Luna');
    }

    public function test_animal_catalog_filters_by_breed(): void
    {
        Animal::factory()->create([
            'name' => 'BengalCat',
            'breed' => 'Kot Bengalski',
            'is_published' => true,
            'published_at' => now(),
        ]);

        Animal::factory()->create([
            'name' => 'BritishCat',
            'breed' => 'Kot Brytyjski',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/koty?breed=Kot+Bengalski');

        $response->assertOk();
        $response->assertSee('Koty Bengalskie z Rodowodem');
        $response->assertSee('BengalCat');
        $response->assertDontSee('BritishCat');
    }

    public function test_animal_profile_page_can_be_rendered(): void
    {
        $animal = Animal::factory()->create([
            'name' => 'Simba',
            'slug' => 'simba',
            'breed' => 'Kot Brytyjski',
            'color' => 'Niebieski',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/koty/simba');

        $response->assertOk();
        $response->assertSee('Simba');
        $response->assertSee('Kot Brytyjski');
        $response->assertSee('Niebieski');
        $response->assertSee('przebadanych rodzicach');
        $response->assertSee('Strona Główna');
        $response->assertSee('Nasze Koty');
    }

    public function test_breeding_animal_profile_shows_breed_dedicated_tests(): void
    {
        $breedingCat = Animal::factory()->create([
            'name' => 'Bella',
            'slug' => 'bella-breeding',
            'breed' => 'Kot Bengalski',
            'status' => \App\Enums\AnimalStatus::Breeding,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/koty/bella-breeding');

        $response->assertOk();
        $response->assertSee('PRA-b, PK-Def n/n');
        $response->assertSee('HCM normal');
    }

    public function test_unpublished_animal_returns_404(): void
    {
        Animal::factory()->create([
            'name' => 'Secret',
            'slug' => 'secret',
            'is_published' => false,
            'published_at' => null,
        ]);

        $response = $this->get('/koty/secret');

        $response->assertNotFound();
    }

    public function test_parent_profile_shows_offspring_section(): void
    {
        $mother = Animal::factory()->female()->breeding()->create([
            'name' => 'Luna',
            'slug' => 'luna-mother',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $child = Animal::factory()->create([
            'name' => 'Barbie',
            'slug' => 'barbie-child',
            'status' => \App\Enums\AnimalStatus::Sold,
            'mother_id' => $mother->id,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/koty/luna-mother');

        $response->assertOk();
        $response->assertSee('Potomstwo kota Luna');
        $response->assertSee('Barbie');
        $response->assertSee('W nowym domu');
    }

    public function test_animal_card_hides_price_and_displays_luxury_value_badge(): void
    {
        Animal::factory()->create([
            'name' => 'Baron Unique',
            'slug' => 'baron-unique',
            'breed' => 'Kot Bengalski',
            'price' => 2700,
            'is_published' => true,
            'published_at' => now(),
            'status' => \App\Enums\AnimalStatus::Available,
        ]);

        $response = $this->get('/koty');

        $response->assertOk();
        $response->assertSee('Baron Unique');
        $response->assertDontSee('animal-card__price-badge', false);
        $response->assertDontSee('2 700 zł');
        $response->assertSee('animal-card__value-badge', false);
        $response->assertSee('✨ Rodowód • Komplet szczepień');
        $response->assertSee('Poznaj bliżej i sprawdź →');

        // Profile must show the price
        $profileResponse = $this->get('/koty/baron-unique');
        $profileResponse->assertOk();
        $profileResponse->assertSee('2 700 zł');
        $profileResponse->assertSee('Cena kociaka „na kolanka”:');
    }

    public function test_cattery_page_renders_stud_gallery_links_and_cta(): void
    {
        $response = $this->get('/o-hodowli');

        $response->assertOk();
        $response->assertSee('galeria');
        $response->assertSee('gallery-grid', false);
        $response->assertSee('/koty/stas');
        $response->assertSee('/koty/bars');
        $response->assertSee('/koty/baltazar');
        $response->assertSee('Poznaj nasze koty hodowlane i dostępne kocięta');
    }

    public function test_animal_profile_renders_carousel_lightbox_with_arrows_and_counter(): void
    {
        $animal = Animal::factory()->create([
            'name' => 'Baron Lightbox',
            'slug' => 'baron-lightbox',
            'breed' => 'Kot Bengalski',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/koty/baron-lightbox');

        $response->assertOk();
        $response->assertSee('animal-gallery-main', false);
        $response->assertSee('openAnimalLightbox()', false);
        $response->assertSee('animal-lightbox-prev', false);
        $response->assertSee('animal-lightbox-next', false);
        $response->assertSee('animal-lightbox-counter', false);
        $response->assertSee('navigateAnimalLightbox', false);
    }
}
