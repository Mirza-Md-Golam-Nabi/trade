<?php
namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\UserSeeder;
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
        // User::factory(10)->create();

        User::factory()->create([
            'name'  => 'Admin',
            'email' => 'admin@example.com',
            'phone' => '01700000000',
            'role'  => UserRole::Admin,
        ]);

        $this->call([
            CurrencySeeder::class,
            UserSeeder::class,
        ]);
    }
}
