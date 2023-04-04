<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Patient;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PatientTest extends TestCase
{
    use DatabaseTransactions, WithFaker;
    
    /** @test */
    public function it_can_store_a_new_patient()
    {
        $cns = ["244 8364 9799 0000", "709 3767 6504 0000", "810 8852 0377 0000", ];
        $data = [
            'name' => $this->faker->name,
            'name_mother' => $this->faker->name,
            'date_birth' => $this->faker->date('d/m/Y'),
            'cpf' => $this->faker->numerify("###########"),
            'cns' => $cns[0],

            'zip_code' => strpos($this->faker->postcode, '-') !== false ? str_replace('-', '', $this->faker->postcode) : $this->faker->postcode,
            'address' => $this->faker->streetName,
            'number' => $this->faker->buildingNumber,
            'complement' => $this->faker->secondaryAddress,
            'district' => $this->faker->citySuffix,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
        ];

        $response = $this->post('/api/patients', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('patients', [
            'name' => $data['name'],
            'name_mother' => $data['name_mother'],
            'date_birth' => Carbon::createFromFormat('d/m/Y', $data['date_birth'])->format('Y-m-d'),
            'cpf' => $data['cpf'],
            'cns' => $data['cns'],
        ]);
        $this->assertDatabaseHas('addresses', [
            'zip_code' => strpos($data['zip_code'], '-') !== false ? str_replace('-', '', $data['zip_code']) : $data['zip_code'],
            'address' => $data['address'],
            'number' => $data['number'],
            'complement' => $data['complement'],
            'district' => $data['district'],
            'city' => $data['city'],
            'state' => $data['state'],
        ]);
    }

    /** @test */
    public function test_it_show_patient()
    {
        $patient = Patient::factory()->create();

        $response = $this->get('/api/patients/' . $patient->id);
        $response->assertStatus(200);

        $response->assertJsonFragment([
            'name' => $patient->name,
            'cpf' => $patient->cpf,
        ]);
    }

    /** @test */
    public function it_can_list_all_patients_with_pagination()
    {
        $patients = Patient::factory(10)->create();
        $response = $this->get('/api/patients');

        $response->assertSuccessful();
        $response->assertJsonCount(10, 'data');
    }

    /** @test */
    public function testSearchByName()
    {
        $patients = Patient::factory(10)->create();

        $response = $this->getJson('/api/patients?search=' . $patients[0]->name);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => $patients[0]->name]);
    }

    /** @test */
    public function testSearchByCpf()
    {
        $patients = Patient::factory(10)->create();

        $response = $this->getJson('/api/patients?search=' . $patients[0]->cpf);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['cpf' => $patients[0]->cpf]);
    }

    /** @test */
    public function it_can_delete_a_patient()
    {
        $patient = Patient::factory()->create();
        $response = $this->delete('/api/patients/' . $patient->id);

        $response->assertStatus(200);
        $this->assertDeleted($patient);
    }

    /** @test */
    public function test_it_can_update_a_patient()
    {
        $patient = Patient::factory()->create();

        $newPatientData = ['name' => 'New Name', 'name_mother' => 'New Mother Name', 'date_birth' => '01/01/1990'];
        $newAddressData = ['zip_code' => '12345678', 'address' => 'New Address', 'number' => '1234', 'district' => 'New District', 'city' => 'New City', 'state' => 'NA'];

        $response = $this->put("/api/patients/{$patient->id}", array_merge($newPatientData, $newAddressData));

        $response->assertStatus(200);

        $this->assertDatabaseHas('patients', $newPatientData);
        $this->assertDatabaseHas('addresses', $newAddressData + ['patient_id' => $patient->id]);

    }
}
