<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Patient;
use App\Models\Address;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PatientsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $patient = new Patient([
            'name' => $row['name'],
            'name_mother' => $row['name_mother'],
            'date_birth' => Date::excelToDateTimeObject($row['date_birth'])->format('d/m/Y'),
            'cpf' => $row['cpf'],
            'cns' => $row['cns'],
        ]);
        $patient->save(); 

        $address = new Address([
            'zip_code' => $row['zip_code'],
            'address' => $row['address'],
            'number' => $row['number'],
            'district' => $row['district'],
            'city' => $row['city'],
            'state' => $row['state'],
            'complement' => $row['complement'],
            'patient_id' => $patient->id,
        ]);
        $patient->address()->save($address);

        $newPatients[] = ['patient' => $patient, 'address' => $address];
    }

    private function convertDateOfBirth($value)
    {
        return Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }
}