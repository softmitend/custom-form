<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL');
        $adminPassword = env('ADMIN_PASSWORD');

        if ($adminEmail && $adminPassword) {
            User::query()->updateOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => env('ADMIN_NAME', 'Admin'),
                    'password' => $adminPassword,
                    'is_admin' => true,
                ],
            );
        }
    }
}
