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
        $profileResponse->assertDontSee('KOCIAK W OPCJI „NA KOLANKA”');
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
        $response->assertSee('KOCIAK W OPCJI „NA KOLANKA”');
        $response->assertSee('2 700 zł');
        $response->assertSee('W pakiecie z maluchem: wielopokoleniowy rodowód SHiOZ, książeczka zdrowia');

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
        $response->assertSee('Zobacz profil →');
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

    public function test_edge_case_negative_and_zero_prices_are_treated_as_null_and_hidden(): void
    {
        $catZero = Animal::factory()->create([
            'name' => 'Zero Cat',
            'status' => AnimalStatus::Available,
            'price' => 0,
        ]);
        $this->assertNull($catZero->price);
        $this->assertNull($catZero->formattedPrice());
        $this->assertFalse($catZero->shouldShowPrice());

        $catNegative = Animal::factory()->create([
            'name' => 'Negative Cat',
            'status' => AnimalStatus::Available,
            'price' => -500,
        ]);
        $this->assertNull($catNegative->price);
        $this->assertNull($catNegative->formattedPrice());
        $this->assertFalse($catNegative->shouldShowPrice());
    }

    public function test_edge_case_price_sanitization_with_weird_characters_and_overflow(): void
    {
        $cat = new Animal();

        // Polish formatted currency string
        $cat->price = ' 2 700 zł ';
        $this->assertSame(2700, $cat->price);
        $this->assertSame('2 700 zł', $cat->formattedPrice());

        // Extreme overflow clamp
        $cat->price = '99999999999';
        $this->assertSame(1000000, $cat->price);
        $this->assertSame('1 000 000 zł', $cat->formattedPrice());

        // XSS / Malformed string with no digits
        $cat->price = "<script>alert('hack')</script>";
        $this->assertNull($cat->price);
        $this->assertNull($cat->formattedPrice());

        // Negative sign in string
        $cat->price = '-2500 zł';
        $this->assertNull($cat->price);
    }

    public function test_edge_case_age_calculation_handles_future_dates_and_polish_inflections(): void
    {
        $cat = new Animal();

        // Null DOB
        $cat->date_of_birth = null;
        $this->assertNull($cat->age());

        // Future DOB (planned litter)
        $cat->date_of_birth = now()->addMonths(2);
        $this->assertSame('Oczekiwany miot', $cat->age());

        // Born today
        $cat->date_of_birth = now();
        $this->assertSame('Dziś', $cat->age());

        // 1 month
        $cat->date_of_birth = now()->subMonth();
        $this->assertSame('1 miesiąc', $cat->age());

        // 3 months
        $cat->date_of_birth = now()->subMonths(3);
        $this->assertSame('3 miesiące', $cat->age());

        // 6 months
        $cat->date_of_birth = now()->subMonths(6);
        $this->assertSame('6 miesięcy', $cat->age());

        // 1 year
        $cat->date_of_birth = now()->subYear();
        $this->assertSame('1 rok', $cat->age());

        // 2 years
        $cat->date_of_birth = now()->subYears(2);
        $this->assertSame('2 lata', $cat->age());

        // 5 years
        $cat->date_of_birth = now()->subYears(5);
        $this->assertSame('5 lat', $cat->age());
    }

    public function test_concurrency_and_race_condition_protection_on_quick_price_endpoint(): void
    {
        $kitten = Animal::factory()->create([
            'name' => 'Concurrent Kitten',
            'status' => AnimalStatus::Available,
            'price' => 2000,
        ]);

        // Simulating simultaneous atomic lock and update
        $response = $this->actingAs($this->adminUser)
            ->patch(route('backend.animals.quick-price', $kitten), [
                'price' => '2 900 zł',
            ]);

        $response->assertRedirect();
        $kitten->refresh();
        $this->assertSame(2900, $kitten->price);

        // Validation rejection for excessively long / malicious strings
        $badResponse = $this->actingAs($this->adminUser)
            ->patch(route('backend.animals.quick-price', $kitten), [
                'price' => str_repeat('a', 60),
            ]);

        $badResponse->assertSessionHasErrors('price');
    }
}

