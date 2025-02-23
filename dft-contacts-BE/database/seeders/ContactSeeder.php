<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Description: Seed the contacts table
     * @return void
     */
    public function run(): void
    {
        Contact::factory(30)->create();
    }
}
