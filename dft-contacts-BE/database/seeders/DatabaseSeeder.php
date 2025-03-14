<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\ContactSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with the data from the ContactSeeder.
     */
    public function run(): void
    {
        $this->call([ContactSeeder::class]);
    }
}
