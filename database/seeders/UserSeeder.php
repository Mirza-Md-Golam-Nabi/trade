<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = $this->users();
        foreach ($users as $user) {
            User::factory()
            ->hasAccounts(2, function (array $attributes, User $user) {
                return ['user_id' => $user->id];
            })
            ->create($user);
        }
    }

    protected function users(): array
    {
        return [
            [
                'name'  => 'User 1',
                'email' => 'user1@example.com',
                'phone' => '01800000001',
                'role'  => UserRole::User,
            ],
            [
                'name'  => 'User 2',
                'email' => 'user2@example.com',
                'phone' => '01800000002',
                'role'  => UserRole::User,
            ],
        ];
    }
}
