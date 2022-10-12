<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Student;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(DormitorySeeder::class);
        $this->call(FormalEducationSeeder::class);
        $this->call(MadinEducationSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(UserSeeder::class);
        Student::factory(30)->create();
    }
}
