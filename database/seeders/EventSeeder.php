<?php

namespace Database\Seeders;

use Database\Factories\EventFactory;
use Database\Factories\EventsFactory;
use Database\Factories\EventsSeederFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EventsFactory::new()->count(10)->create();
    }
}
