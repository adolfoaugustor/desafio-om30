<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Patient;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PatientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $cns = ["244 8364 9799 0000", "709 3767 6504 0000", "810 8852 0377 0000", "247 3353 7488 0000"];
        // $cpf = ["83222863172", "32490786202", "25732731563", "59779636102"];
        $faker = Faker::create();
        
        for ($i = 0; $i < 20; $i++) {
            $patient = Patient::create([
                'name' => $faker->name(),
                'name_mother' => $faker->name(),
                'date_birth' => $faker->date('d/m/Y'),
                'cpf' => $faker->numerify("###########"),
                'cns' => $faker->numerify("### #### #### ####"),
            ]);
            $patient->save();

            $address = Address::create([
                'zip_code' => $faker->numerify('#####-###'),
                'address' => $faker->streetAddress(),
                'number' => $faker->numberBetween(1, 9999),
                'complement' => $faker->optional()->secondaryAddress(),
                'district' => $faker->citySuffix(),
                'city' => $faker->city(),
                'state' => $faker->stateAbbr(),
                'patient_id' => $patient->id
            ]);
            $address->save();
        }
    }
}
