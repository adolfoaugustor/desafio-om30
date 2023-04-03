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
        'image_patient'
    ];

    public function mapping(): array
    {
        return [
            'name' => 'A1',
            'name_mother' => 'B1',
            'date_birth' => 'C1',
            'cpf' => 'D1',
            'cns' => 'E1',
            'image_patient' => 'F1',
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
                'image_patient' => strval($row['image_patient'])
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
