<?php

namespace Database\Factories;

use App\Models\Dormitory;
use App\Models\FormalEducation;
use App\Models\MadinEducation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->name(),
            'user_id' => User::all()->random()->id,
            'nik' => $this->faker->word(),
            'nis' => rand(222222, 999999),
            'tanggal_lahir' => $this->faker->date(),
            'tempat_lahir' => $this->faker->word(),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),

            // alamat rumah
            'alamat' => $this->faker->name(),
            'rtrw' => $this->faker->name(),
            'desa' => $this->faker->streetName(),
            'kota' => $this->faker->city(),
            'kecamatan' => $this->faker->city(),
            'provinsi' => $this->faker->city(),
            'kode_pos' => $this->faker->name(),
            'madin_education_id' => MadinEducation::all()->random()->id,
            'formal_education_id' => FormalEducation::all()->random()->id,
            'dormitory_id' => Dormitory::all()->random()->id,
            'room' => rand(1, 9),
        ];
    }
}
