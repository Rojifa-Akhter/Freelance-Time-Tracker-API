<?php
namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::insert([
            [
                'title'        => 'Website Redesign',
                'client_id'   => 1,
                'description' => 'Redesigning corporate site',
            ],
            [
                'title'        => 'Mobile App Dev',
                'client_id'   => 2,
                'description' => 'iOS/Android development',
            ],
        ]);
    }
}
