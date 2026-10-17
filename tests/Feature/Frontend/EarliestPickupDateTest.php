<?php

declare(strict_types=1);

namespace Tests\Feature\Frontend;

use App\Enums\AnimalGender;
use App\Enums\AnimalStatus;
use App\Enums\AnimalType;
use App\Models\Animal;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EarliestPickupDateTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['slug' => 'admin']);
        $this->adminUser = User::factory()->create([
            'role_id' => $adminRole->id,
            'is_active' => true,
        ]);
    }

    public function test_explicit_earliest_pickup_date_overrides_standard_math(): void
    {
        $now = Carbon::parse('2026-10-17 12:00:00');

        // Kitten born 5 weeks ago, but breeder specifies pickup in 4 weeks (2026-11-14)
        $kitten = Animal::factory()->create([
            'name' => 'Ada',
            'slug' => 'ada-test',
            'breed' => 'Kot Brytyjski',
            'status' => AnimalStatus::Available,
            'date_of_birth' => $now->copy()->subWeeks(5),
            'earliest_pickup_date' => $now->copy()->addWeeks(4)->format('Y-m-d'),
        ]);

        $this->assertSame(4, $kitten->weeksUntilPickupWindow($now));
        $this->assertSame('Odbiór: za ok. 4 tyg.', $kitten->pickupTimelineLabel($now));
        $this->assertSame('Dostępny do rezerwacji', $kitten->pickupBadgeLabel($now));
        $this->assertSame('warning', $kitten->pickupBadgeVariant($now));
        $this->assertTrue($kitten->isEarlyReservation($now));
        $this->assertFalse($kitten->isReadyNow($now));

        $window = $kitten->pickupWindow();
        $this->assertNotNull($window);
        $this->assertSame('14.11.2026', $window['start']->format('d.m.Y'));
        $this->assertSame('28.11.2026', $window['end']->format('d.m.Y'));
        $this->assertSame('14.11.2026 – 28.11.2026', $kitten->pickupWindowFormatted());
    }

    public function test_kitten_becomes_ready_now_when_reaching_earliest_pickup_date(): void
    {
        $pickupDate = Carbon::parse('2026-11-14');

        $kitten = Animal::factory()->create([
            'status' => AnimalStatus::Available,
            'date_of_birth' => Carbon::parse('2026-09-09'),
            'earliest_pickup_date' => $pickupDate->format('Y-m-d'),
        ]);

        // On pickup day
        $onPickupDay = $pickupDate->copy()->setTime(10, 0, 0);
        $this->assertSame(0, $kitten->weeksUntilPickupWindow($onPickupDay));
        $this->assertSame('Gotowy do odbioru od zaraz', $kitten->pickupTimelineLabel($onPickupDay));
        $this->assertSame('Gotowy do odbioru', $kitten->pickupBadgeLabel($onPickupDay));
        $this->assertSame('gold', $kitten->pickupBadgeVariant($onPickupDay));
        $this->assertFalse($kitten->isEarlyReservation($onPickupDay));
        $this->assertTrue($kitten->isReadyNow($onPickupDay));
        $this->assertTrue($kitten->isInPickupWindow($onPickupDay));

        // 1 week after pickup start (still inside 2-week window)
        $oneWeekAfter = $pickupDate->copy()->addWeeks(1);
        $this->assertTrue($kitten->isInPickupWindow($oneWeekAfter));
        $this->assertTrue($kitten->isReadyNow($oneWeekAfter));

        // Past 2-week window
        $threeWeeksAfter = $pickupDate->copy()->addWeeks(3);
        $this->assertFalse($kitten->isInPickupWindow($threeWeeksAfter));
        $this->assertTrue($kitten->isReadyNow($threeWeeksAfter));
    }

    public function test_null_earliest_pickup_date_transparently_falls_back_to_standard_math(): void
    {
        $now = Carbon::parse('2026-10-17 12:00:00');

        // Kitten 4 weeks old, no earliest_pickup_date -> DOB + 12 weeks = 8 weeks remaining
        $youngKitten = Animal::factory()->create([
            'status' => AnimalStatus::Available,
            'date_of_birth' => $now->copy()->subWeeks(4),
            'earliest_pickup_date' => null,
        ]);

        $this->assertSame(8, $youngKitten->weeksUntilPickupWindow($now));
        $this->assertSame('Odbiór: za ok. 8 tyg.', $youngKitten->pickupTimelineLabel($now));
        $this->assertSame('Dostępny do rezerwacji', $youngKitten->pickupBadgeLabel($now));
        $this->assertTrue($youngKitten->isEarlyReservation($now));
        $this->assertFalse($youngKitten->isReadyNow($now));

        // 12 weeks old -> ready now
        $readyKitten = Animal::factory()->create([
            'status' => AnimalStatus::Available,
            'date_of_birth' => $now->copy()->subWeeks(12),
            'earliest_pickup_date' => null,
        ]);

        $this->assertSame(0, $readyKitten->weeksUntilPickupWindow($now));
        $this->assertSame('Gotowy do odbioru od zaraz', $readyKitten->pickupTimelineLabel($now));
        $this->assertSame('Gotowy do odbioru', $readyKitten->pickupBadgeLabel($now));
        $this->assertFalse($readyKitten->isEarlyReservation($now));
        $this->assertTrue($readyKitten->isReadyNow($now));
    }

    public function test_public_show_page_renders_custom_pickup_date_and_window(): void
    {
        $now = Carbon::parse('2026-10-17 12:00:00');
        Carbon::setTestNow($now);

        $kitten = Animal::factory()->create([
            'name' => 'Ada',
            'slug' => 'ada-brytyjska',
            'breed' => 'Kot Brytyjski',
            'status' => AnimalStatus::Available,
            'is_published' => true,
            'published_at' => $now->copy()->subDays(2),
            'date_of_birth' => $now->copy()->subWeeks(5),
            'earliest_pickup_date' => '2026-11-14',
        ]);

        $response = $this->get(route('frontend.animals.show', $kitten));

        $response->assertOk();
        $response->assertSee('Dostępny do rezerwacji');
        $response->assertSee('Odbiór: za ok. 4 tyg.');
        $response->assertSee('14.11.2026 – 28.11.2026');

        Carbon::setTestNow();
    }

    public function test_admin_can_save_and_update_earliest_pickup_date_via_cms(): void
    {
        $kitten = Animal::factory()->create([
            'name' => 'Alma',
            'slug' => 'alma-test',
            'breed' => 'Kot Brytyjski',
            'gender' => AnimalGender::Female,
            'status' => AnimalStatus::Available,
            'type' => AnimalType::Cat,
            'date_of_birth' => '2026-09-09',
            'earliest_pickup_date' => null,
            'is_published' => true,
            'is_featured' => false,
        ]);

        // 1. Update earliest_pickup_date via CMS
        $response = $this->actingAs($this->adminUser)
            ->put(route('backend.animals.update', $kitten), [
                'name' => 'Alma',
                'slug' => 'alma-test',
                'breed' => 'Kot Brytyjski',
                'gender' => AnimalGender::Female->value,
                'status' => AnimalStatus::Available->value,
                'type' => AnimalType::Cat->value,
                'date_of_birth' => '2026-09-09',
                'earliest_pickup_date' => '2026-11-14',
                'is_published' => '1',
                'is_featured' => '0',
            ]);

        $response->assertRedirect(route('backend.animals.edit', $kitten));
        $kitten->refresh();
        $this->assertSame('2026-11-14', $kitten->earliest_pickup_date->format('Y-m-d'));

        // 2. Clear earliest_pickup_date (set to null) -> falls back to automatic math
        $clearResponse = $this->actingAs($this->adminUser)
            ->put(route('backend.animals.update', $kitten), [
                'name' => 'Alma',
                'slug' => 'alma-test',
                'breed' => 'Kot Brytyjski',
                'gender' => AnimalGender::Female->value,
                'status' => AnimalStatus::Available->value,
                'type' => AnimalType::Cat->value,
                'date_of_birth' => '2026-09-09',
                'earliest_pickup_date' => null,
                'is_published' => '1',
                'is_featured' => '0',
            ]);

        $clearResponse->assertRedirect(route('backend.animals.edit', $kitten));
        $kitten->refresh();
        $this->assertNull($kitten->earliest_pickup_date);
    }
}
