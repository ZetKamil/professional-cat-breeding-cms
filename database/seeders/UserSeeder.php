<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Full system administrator']
        );

        $seedPassword = (string) env('ADMIN_SEED_PASSWORD', '');
        if ($seedPassword === '') {
            $seedPassword = Str::random(32);
        }

        User::firstOrCreate(
            ['email' => 'kamil.ziemkiewicz.97@gmail.com'],
            [
                'name' => 'Kamil Ziemkiewicz',
                'role_id' => $adminRole->id,
                'is_active' => true,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make($seedPassword),
            ]
        );

        if (app()->environment('local') && User::count() < 10) {
            User::factory()->count(10)->create();
        }
    }
}
