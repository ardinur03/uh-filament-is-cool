<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
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
        User::factory()->create([
            'name' => 'Ardo',
            'email' => 'codewithardi@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        collect([
            [
                'name' => 'HR',
                'description' => 'Human Resources Department',
                'active' => true,
            ],
            [
                'name' => 'IT',
                'description' => 'Information Technology Department',
                'active' => true,
            ],
            [
                'name' => 'Sales',
                'description' => 'Sales Department',
                'active' => true,
            ],
            [
                'name' => 'Marketing',
                'description' => 'Marketing Department',
                'active' => true,
            ],
            [
                'name' => 'Finance',
                'description' => 'Finance Department',
                'active' => false,
            ],
        ])->each(function ($department) {
            Department::create($department);
        });

        collect([
            [
                'name' => 'Software Engineer',
            ],
            [
                'name' => 'Project Manager',
            ],
            [
                'name' => 'Accountant',
            ],
            [
                'name' => 'HR Representative',
            ],
            [
                'name' => 'Sales Representative',
            ],
        ])->each(function ($position) {
            Position::create($position);
        });

        Employee::factory(20)->create();
    }
}
