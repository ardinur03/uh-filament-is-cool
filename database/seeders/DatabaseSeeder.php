<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Department::factory(100)->create();

        User::factory()->create([
            'name' => 'Ardo',
            'email' => 'codewithardi@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
    }
}
