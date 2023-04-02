<?php

namespace App\Http\Controllers\Api;

use Exception;
use Carbon\Carbon;
use App\Rules\CnsRule;
use App\Models\Address;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function version()
    {
        $version = '1.0';

        return response()->json([
            'success' => true,
            'version' => $version,
        ]);
    }

    public function index()
    {
        $patients = Patient::with('address')->get();

        return response()->json(['patients' => $patients], 200);
    }
    
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name'          => ['required','string','max:255'],
            'name_mother'   => ['required','string','max:255'],
            'date_birth'    => ['required'],
            'image_patient' => ['nullable','image','mimes:jpeg,png,jpg,gif','max:2048'],
            'cpf'           => ['required','string','unique:patients'],
            'cns'           => ['required','string',new CnsRule],
            # Address
            'zip_code'      => ['required','string','max:9'],
            'address'       => ['required','string','max:255'],
            'number'        => ['required','numeric'],
            'complement'    => ['nullable','string','max:255'],
            'district'      => ['required','string','max:255'],
            'city'          => ['required','string','max:255'],
            'state'         => ['required','string','max:2'],
        ]);
        
        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 400);
        }

        DB::beginTransaction();

        try {
            
            $patient = new Patient;
            if ($request->hasFile('image_patient')) {
                $image = $request->file('image_patient');
                $name = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = storage_path('app/public/images/patients');
                $image->move($destinationPath, $name);
                $patient->image_patient = $name;
            }
            $patient->name = $request->name;
            $patient->name_mother = $request->name_mother;
            $patient->date_birth = $request->date_birth;
            $patient->cpf = $request->cpf;
            $patient->cns = $request->cns;
            $patient->save();

            $address = new Address;
            $address->zip_code = strpos($request->zip_code, '-') !== false ? str_replace('-', '', $request->zip_code) : $request->zip_code;
            $address->address = $request->address;
            $address->number = $request->number;
            $address->complement = $request->complement;
            $address->district = $request->district;
            $address->city = $request->city;
            $address->state = $request->state;
            $address->patient_id = $patient->id;
            $address->save();

            DB::commit();

            return response()->json(['message' => 'Paciente adicionado com sucesso!'], 201);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Falha ao criar paciente', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $patient = Patient::with('address')->find($id);

        if (!$patient) {
            return response()->json(['message' => 'Paciente não encontrado!'], 404);
        }

        return response()->json(['patient' => $patient], 200);
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
    
        // Validação dos dados recebidos
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'name_mother' => 'required|string|max:255',
            'date_birth'  => 'required|date_format:d/m/Y',

            'zip_code'    => 'required|string|max:9',
            'address'     => 'required|string|max:255',
            'number'      => 'required|string|max:10',
            'complement'  => 'nullable|string|max:255',
            'district'    => 'required|string|max:255',
            'city'        => 'required|string|max:255',
            'state'       => 'required|string|max:2',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $patient->name = $request->input('name');
        $patient->name_mother = $request->input('name_mother');
        $patient->date_birth = $request->input('date_birth');
        $patient->save();

        $address = $patient->address;
        $address->zip_code = $request->input('zip_code');
        $address->address = $request->input('address');
        $address->number = $request->input('number');
        $address->complement = $request->input('complement');
        $address->district = $request->input('district');
        $address->city = $request->input('city');
        $address->state = $request->input('state');
        $address->save();

        return response()->json(['patient' => $patient], 200);
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return response()->json(['message' => 'Paciente deletado com sucesso!']);
    }
}
