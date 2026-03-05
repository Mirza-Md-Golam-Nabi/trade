<?php
namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Database\Seeders\CurrencySeeder;
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

        $users = $this->users();
        foreach ($users as $user) {
            User::factory()->create($user);
        }

        $this->call([
            CurrencySeeder::class,
        ]);
    }

    protected function users(): array
    {
        return [
            [
                'name'  => 'Admin',
                'email' => 'admin@example.com',
                'phone' => '01700000000',
                'role'  => UserRole::Admin,
            ],
            [
                'name'  => 'User',
                'email' => 'user@example.com',
                'phone' => '01800000000',
                'role'  => UserRole::User,
            ],
        ];
    }
}
