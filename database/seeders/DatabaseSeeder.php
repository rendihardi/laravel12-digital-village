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
        // User::factory(10)->create();

        $this->call([HeadOfFamilySeeder::class]);
        $this->call([SocialAssistanceSeeder::class]);
        $this->call([SocialAssistanceRecipientSeeder::class]);
        $this->call([EventSeeder::class]);
        $this->call([EventParticipantSeeder::class]);
        $this->call([DevelopmentSeeder::class]);
        $this->call([DevelopmentApplicantSeeder::class]);
        $this->call([PermissionSeeder::class]);
        $this->call([RoleSeeder::class]);
        $this->call([UserSeeder::class]);
    }
}
