<?php

namespace App\Imports;

use App\Models\Patient;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Imports\HeadingRowExtractor;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class PatientsImport implements ToModel, WithHeadingRow, WithMultipleSheets, WithEvents
{
    use Importable, RegistersEventListeners;
    protected $models;


    // Para processar apenas a primeira planilha (incluir WithMultipleSheets)
    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    private static $header = [
        'name',
        'name_mother',
        'date_birth',
        'cpf',
        'cns',
        'zip_code',
        'address',
        'number',
        'district',
        'city',
        'state',
        'complement'
    ];

    public function mapping(): array
    {
        return [
            'name' => 'A1',
            'name_mother' => 'B1',
            'date_birth' => 'C1',
            'cpf' => 'D1',
            'cns' => 'E1',
            'zip_code' => 'F1',
            'address' => 'G1',
            'number' => 'H1',
            'district' => 'I1',
            'city' => 'J1',
            'state' => 'K1',
            'complement' => 'L1'
        ];
    }

    public function model(array $row)
    {
        if(array_filter($row)){ // previne processar linhas vazias que aparecem quando se exclui linhas no excel.
            $patient = new Patient([
                'name'          => strval($row['name']),
                'name_mother'   => strval($row['name_mother']),
                'date_birth'    => strval($row['date_birth']),
                'cpf'           => strval($row['cpf']),
                'cns'           => strval($row['cns']),
                'zip_code'      => strval($row['zip_code']),
                'address'       => strval($row['address']),
                'number'        => strval($row['number']),
                'district'      => strval($row['district']),
                'city'          => strval($row['city']),
                'state'         => strval($row['state']),
                'complement'    => strval($row['complement'])
            ]);
            $this->models[] = $patient;
        }
    }

    public function toModels(string $filename)
    {
       $this->import($filename);

        return $this->models;
    }
}
