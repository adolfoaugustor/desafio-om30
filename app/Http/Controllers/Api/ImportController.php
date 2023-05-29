<?php

namespace App\Http\Controllers\Api;

use App\Models\Patient;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Imports\PatientsImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Shared\Date;
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
            try {
                DB::beginTransaction();

                foreach ($patients_xlsx as $patientData) {
                    $patient = new Patient([
                        'name' => $patientData['name'],
                        'name_mother' => $patientData['name_mother'],
                        'date_birth' => Date::excelToDateTimeObject($patientData['date_birth'])->format('d/m/Y'),
                        'cpf' => $patientData['cpf'],
                        'cns' => $patientData['cns'],
                    ]);
                    $patient->save(); 

                    $address = new Address([
                        'zip_code' => $patientData['zip_code'],
                        'address' => $patientData['address'],
                        'number' => $patientData['number'],
                        'district' => $patientData['district'],
                        'city' => $patientData['city'],
                        'state' => $patientData['state'],
                        'complement' => $patientData['complement'],
                        'patient_id' => $patient->id,
                    ]);
                    $patient->address()->save($address);

                    $newPatients[] = ['patient' => $patient, 'address' => $address];
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                if ($e instanceof \Illuminate\Database\QueryException) {
                    $errorMessage = $e->getMessage();
                } else {
                    $errorMessage = 'Ocorreu um erro durante a importação dos dados.';
                }
                return response()->json(['error' => $errorMessage], 500);
            }

            return response()->json([
                'message' => 'Importação realizada com sucesso. '.count($newPatients).' pacientes criados.'
            ], 200);
        }
    }
}
