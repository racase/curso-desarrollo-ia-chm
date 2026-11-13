<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Demo users. All of them use the password "password".
     *
     * @var list<array{name: string, email: string, role: UserRole}>
     */
    public const USERS = [
        ['name' => 'Ana Admin', 'email' => 'admin@infraticket.test', 'role' => UserRole::Admin],
        ['name' => 'Tomás Técnico', 'email' => 'tecnico1@infraticket.test', 'role' => UserRole::Technician],
        ['name' => 'Teresa Técnica', 'email' => 'tecnico2@infraticket.test', 'role' => UserRole::Technician],
        ['name' => 'Sara Solicitante', 'email' => 'solicitante1@infraticket.test', 'role' => UserRole::Requester],
        ['name' => 'Sergio Solicitante', 'email' => 'solicitante2@infraticket.test', 'role' => UserRole::Requester],
    ];

    /**
     * Seed the application's database. Safe to run more than once.
     */
    public function run(): void
    {
        foreach (self::USERS as $user) {
            if (User::where('email', $user['email'])->exists()) {
                continue;
            }

            User::factory()->create($user);
        }
    }
}
