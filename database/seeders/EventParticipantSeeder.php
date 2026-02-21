<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\HeadOfFamily;
use Database\Factories\EventParticipantFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $events = Event::limit(10)->get();
        $headOfFamilies = HeadOfFamily::limit(10)->get();

        foreach ($events as $events) {
         foreach ($headOfFamilies as $headOfFamily) {
         EventParticipantFactory::new()->create([
             'head_of_family_id' => $headOfFamily->id,
             'event_id' => $events->id,
             ]);
          }
        }
    }
}
