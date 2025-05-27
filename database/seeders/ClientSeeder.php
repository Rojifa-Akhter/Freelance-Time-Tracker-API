<?php
namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::insert([
            ['name' => 'Client A', 'email' => 'clienta@gmail.com'],
            ['name' => 'Client B', 'email' => 'clientb@gmail.com'],
        ]);
    }
}
