<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        Translation::factory()->count(10000)->create();

        $batchSize = 1000; // Optimize for memory
        $totalRecords = 100000; // 100k records

        $this->command->getOutput()->progressStart($totalRecords);

        for ($i = 0; $i < $totalRecords; $i += $batchSize) {
            Translation::factory()
                ->count(min($batchSize, $totalRecords - $i))
                ->make()
                ->chunk(500) // Further chunking for large inserts
                ->each(function ($chunk) {
                    Translation::insert($chunk->toArray());
                });

            $this->command->getOutput()->progressAdvance($batchSize);
        }

        $this->command->getOutput()->progressFinish();
    }
}
