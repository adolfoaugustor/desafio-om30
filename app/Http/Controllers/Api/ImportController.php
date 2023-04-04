<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\PatientsAddressImport;
use App\Imports\PatientsImport;
use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\Patient;
use Carbon\Carbon;
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
            
            $patients_xlsx = (new PatientsImport)->toModels($filePath);

            if(!$patients_xlsx){
                throw new \Exception("A planilha deve conter pelo menos um registro.");
            }

            $newPatient = [];
            foreach($patients_xlsx as $patient){
                $newPatient = $patient;

            }
        }

        return response()->json(['message' => 'Importação realizada com sucesso.'], 200);
    }
}
