<?php

namespace Tests\Feature\Frontend;

use App\Enums\AnimalGender;
use App\Enums\AnimalStatus;
use App\Models\Animal;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PriceAndFunnelUnificationTest extends TestCase
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
    }

    public function test_admin_can_update_quick_price_via_patch_endpoint(): void
    {
        $kitten = Animal::factory()->create([
            'name' => 'Baron Test',
            'breed' => 'Kot Bengalski',
            'status' => AnimalStatus::Available,
            'is_published' => true,
            'price' => 2700,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->patch(route('backend.animals.quick-price', $kitten), [
                'price' => '2 850 zł',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $kitten->refresh();
        $this->assertSame(2850, $kitten->price);
        $this->assertSame('2 850 zł', $kitten->formattedPrice());
    }

    public function test_admin_can_clear_price_via_quick_price_endpoint(): void
    {
        $kitten = Animal::factory()->create([
            'name' => 'Kociak Test',
            'price' => 2500,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->patch(route('backend.animals.quick-price', $kitten), [
                'price' => '',
            ]);

        $response->assertRedirect();

        $kitten->refresh();
        $this->assertNull($kitten->price);
        $this->assertNull($kitten->formattedPrice());
    }

    public function test_cat_cards_display_luxury_price_badge_for_kittens_with_price(): void
    {
        $kitten = Animal::factory()->create([
            'name' => 'Cyprian',
            'breed' => 'Kot Bengalski',
            'status' => AnimalStatus::Available,
            'is_published' => true,
            'price' => 3000,
            'slug' => 'cyprian-test',
        ]);

        $response = $this->get(route('frontend.animals.index'));
        $response->assertOk();
        $response->assertSee('3 000 zł');
        $response->assertSee('animal-card__price-badge', false);
    }

    public function test_breeding_cats_do_not_display_price_badge_even_if_price_is_set(): void
    {
        $adultCat = Animal::factory()->create([
            'name' => 'Atos Reproduktor',
            'breed' => 'Kot Bengalski',
            'status' => AnimalStatus::Breeding,
            'is_published' => true,
            'price' => 3500,
            'slug' => 'atos-test',
        ]);

        $this->assertFalse($adultCat->shouldShowPrice());

        $indexResponse = $this->get(route('frontend.animals.index'));
        $indexResponse->assertOk();
        $indexResponse->assertDontSee('3 500 zł');

        $profileResponse = $this->get(route('frontend.animals.show', $adultCat));
        $profileResponse->assertOk();
        $profileResponse->assertDontSee('3 500 zł');
        $profileResponse->assertDontSee('CENA KOCIAKA (OPCJA „NA KOLANKA”)');
    }

    public function test_cat_profile_page_displays_luxury_price_box_and_messenger_primary_cta(): void
    {
        $kitten = Animal::factory()->create([
            'name' => 'Baron',
            'breed' => 'Kot Bengalski',
            'status' => AnimalStatus::Available,
            'gender' => AnimalGender::Male,
            'is_published' => true,
            'price' => 2700,
            'slug' => 'baron-profile-test',
        ]);

        $response = $this->get(route('frontend.animals.show', $kitten));
        $response->assertOk();

        // 1. Price Box and Subtext
        $response->assertSee('animal-profile__price-box', false);
        $response->assertSee('CENA KOCIAKA (OPCJA „NA KOLANKA”)');
        $response->assertSee('2 700 zł');
        $response->assertSee('W cenie: wielopokoleniowy rodowód SHiOZ, książeczka zdrowia');

        // 2. Messenger-First Primary CTA with pre-filled inquiry
        $response->assertSee('animal-profile__btn--messenger', false);
        $response->assertSee('Zapytaj o kociaka lub umów wideo-wizytę (Messenger)');
        $response->assertSee('m.me/61580668026948', false);
        $response->assertSee(urlencode('Dzień dobry! Piszę w sprawie kociaka: Baron (2 700 zł). Czy jest aktualnie wolny?'));

        // 3. Secondary WhatsApp Button
        $response->assertSee('animal-profile__btn--whatsapp', false);
        $response->assertSee('WhatsApp (+48 514 153 204)');
        $response->assertSee('wa.me/48514153204', false);

        // 4. Video-visit box copy
        $response->assertSee('Zobacz kota na żywo (Wideo-wizyta)');
        $response->assertSee('Połącz się bezpośrednio przez Messenger lub WhatsApp');

        // 5. Meta Pixel contact event
        $response->assertSee("fbq('track', 'Contact'", false);
    }

    public function test_cat_profile_renders_valid_product_offer_schema_for_priced_kittens(): void
    {
        $kitten = Animal::factory()->create([
            'name' => 'Carlos',
            'breed' => 'Kot Bengalski',
            'status' => AnimalStatus::Available,
            'is_published' => true,
            'price' => 3000,
            'slug' => 'carlos-schema-test',
        ]);

        $response = $this->get(route('frontend.animals.show', $kitten));
        $response->assertOk();

        $content = $response->getContent();
        $this->assertStringContainsString('"@type": "Product"', $content);
        $this->assertStringContainsString('"price": "3000.00"', $content);
        $this->assertStringContainsString('"priceCurrency": "PLN"', $content);
        $this->assertStringContainsString('https://schema.org/InStock', $content);
    }

    public function test_quiz_result_screen_has_single_profile_button_per_kitten_and_gold_messenger_contact_block(): void
    {
        $bengal = Animal::factory()->create([
            'name' => 'Baron Quiz',
            'breed' => 'Kot Bengalski',
            'status' => AnimalStatus::Available,
            'is_published' => true,
            'price' => 2700,
            'slug' => 'baron-quiz-test',
        ]);

        $response = $this->get(route('quiz'));
        $response->assertOk();

        // 1. Verify top duplicate CTA block was removed
        $response->assertDontSee('quiz-result__immediate-cta', false);
        $response->assertDontSee('quizCtaMessengerTop', false);
        $response->assertDontSee('quizCtaWhatsappTop', false);

        // 2. Verify matched kitten row displays price and single profile button
        $response->assertSee('2 700 zł');
        $response->assertSee('quiz-cat-item__btn-profile', false);
        $response->assertSee('Zobacz profil, zdjęcia i filmiki →');
        $response->assertSee(route('frontend.animals.show', $bengal));

        // 3. Verify single contact block has luxury gold Messenger button
        $response->assertSee('quiz-cta-btn--gold', false);
        $response->assertSee('Napisz na Messengerze (Zapytaj o kociaki lub wideo-wizytę)');
        $response->assertSee('m.me/61580668026948', false);

        // 4. Secondary WhatsApp subtle outline button
        $response->assertSee('quiz-cta-btn--outline', false);
        $response->assertSee('Napisz na WhatsApp (+48 514 153 204)');
        $response->assertSee('wa.me/48514153204', false);

        // 5. Reassurance micro-copy
        $response->assertSee('Odpowiadamy w kilka minut • Wideo-wizytę z kociakami możemy połączyć również bezpośrednio na Messengerze.');

        // 6. Refined Facebook follower card
        $response->assertSee('Nie planujesz zakupu od razu? Zobacz codzienne filmiki z kociakami!');
        $response->assertSee('Zaobserwuj naszą Hodowlę na Facebooku');
        $response->assertSee('facebook.com/profile.php?id=61580668026948');
    }

    public function test_global_conversion_cta_has_messenger_primary_and_whatsapp_secondary_and_fb_link(): void
    {
        $response = $this->get(route('home'));
        $response->assertOk();

        // Primary Gold Messenger button
        $response->assertSee('cta-block__btn--messenger', false);
        $response->assertSee('Napisz lub umów wideo-wizytę (Messenger)');
        $response->assertSee('m.me/61580668026948', false);

        // Secondary WhatsApp button
        $response->assertSee('cta-block__btn--whatsapp', false);
        $response->assertSee('Kontakt przez WhatsApp');
        $response->assertSee('wa.me/48514153204', false);

        // Subtle Facebook follower link below
        $response->assertSee('cta-block__fb-link', false);
        $response->assertSee('Obserwuj nas na Facebooku, by oglądać codzienne relacje z hodowli →');
        $response->assertSee('facebook.com/profile.php?id=61580668026948', false);
    }
}
