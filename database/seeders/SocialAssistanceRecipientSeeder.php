<?php

namespace Database\Seeders;

use App\Models\HeadOfFamily;
use App\Models\SocialAssistance;
use Database\Factories\SocialAssistanceRecipientFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialAssistanceRecipientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $socialAssistances = SocialAssistance::limit(1)->get();
        $headOfFamilies = HeadOfFamily::limit(1)->get();

        foreach ($socialAssistances as $socialAssistance) {
         foreach ($headOfFamilies as $headOfFamily) {
         SocialAssistanceRecipientFactory::new()->create([
               'head_of_family_id' => $headOfFamily->id,
             'social_assistance_id' => $socialAssistance->id,
             ]);
          }
        }
    }
}
