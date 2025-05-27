<?php

namespace Database\Seeders;

use App\Models\TimeLog;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $start = Carbon::now()->subDays(5);

        for ($i = 0; $i < 5; $i++) {
            TimeLog::create([
                'user_id' => 1,
                'project_id' => ($i % 2) + 1, 
                'start_time' => $start->copy()->addHours($i),
                'end_time' => $start->copy()->addHours($i + 2),
                'description' => "Worked on feature #$i",
                'hours' => 2,
                'tag' => $i % 2 === 0 ? 'billable' : 'non-billable',
            ]);
        }
    }
}
