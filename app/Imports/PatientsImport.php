<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Patient;
use Maatwebsite\Excel\Concerns\ToModel;
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
        return new Patient([
            'name'          => strval($row['name']),
            'name_mother'   => strval($row['name_mother']),
            'date_birth'    => $this->convertDateOfBirth($row['date_birth']),
            // 'date_birth'    => strval($row['date_birth']),
            'cpf'           => strval($row['cpf']),
            'cns'           => strval($row['cns']),
            'zip_code'      => strval($row['zip_code']),
            'address'       => strval($row['address']),
            'number'        => strval($row['number']),
            'district'      => strval($row['district']),
            'city'          => strval($row['city']),
            'state'         => strval($row['state']),
            'complement'    => strval($row['complement']),
        ]);
    }

    private function convertDateOfBirth($value)
    {
        return Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }
}