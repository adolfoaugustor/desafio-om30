<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'name_mother' => $this->faker->name,
            'date_birth' => $this->faker->date('Y-m-d'),
            'cpf' => $this->faker->unique()->numerify('###########'),
            'cns' => $this->faker->unique()->numerify('### #### #### ####'),
            'image_patient' => $this->faker->image
        ];

    }

    public function configure()
    {
        return $this->afterCreating(function (Patient $patient) {
            Address::factory()->create(['patient_id' => $patient->id]);
        });
    }
}
