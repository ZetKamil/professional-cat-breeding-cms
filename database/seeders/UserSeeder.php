<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'syntraprogrammeurs@gmail.com'],
            [
                'name' => 'Tom',
                'role_id' => 1,
                'is_active' => 1,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('12345678'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        if (User::count() < 10) {
            User::factory()->count(10)->create();
        }
    }
}
