<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\PatientsImport;
use Illuminate\Http\Request;
use App\Models\Patient;
use Maatwebsite\Excel\Facades\Excel as Excel;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            if(!in_array($file->getClientOriginalExtension(), ['xls', 'xlsx'])){
                throw new \Exception("Formato de arquivo não suportado. Formatos suportados: xls e xlsx.");
            }

            $filePath = $file->storeAs('envios', $file->getClientOriginalExtension());
            
            $patients_xlsx = Excel::toArray(new PatientsImport, $filePath, null, \Maatwebsite\Excel\Excel::XLSX)[0];
            
            if(!$patients_xlsx){
                throw new \Exception("A planilha deve conter pelo menos um registro.");
            }

            $newPatients = [];
            foreach ($patients_xlsx as $patientData) {
                $patient = new Patient([
                    'name'          => $patientData['name'],
                    'name_mother'   => $patientData['name_mother'],
                    'date_birth'    => $patientData['date_birth'],
                    'cpf'           => $patientData['cpf'],
                    'cns'           => $patientData['cns'],
                    'zip_code'      => $patientData['zip_code'],
                    'address'       => $patientData['address'],
                    'number'        => $patientData['number'],
                    'district'      => $patientData['district'],
                    'city'          => $patientData['city'],
                    'state'         => $patientData['state'],
                    'complement'    => $patientData['complement'],
                ]);
            
                $newPatients[] = $patient;
            }
            dd($newPatients);
        }

        return response()->json(['message' => 'Importação realizada com sucesso.'], 200);
    }
}
