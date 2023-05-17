<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            "مخبر",
            "اشعة",
            "قثطرة",
            "حرس",
            "اداريين",
        ];
        for ($i = 0; $i < count($sections); $i++) {
            Section::create([
                "name" => $sections[$i]
            ]);
        }

    }
}
