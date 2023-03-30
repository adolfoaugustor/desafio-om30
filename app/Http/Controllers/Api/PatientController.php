<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Address;

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
        $patients = Patient::all();

        return response()->json([
            'success' => true,
            'data' => $patients,
        ]);
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'image_patient' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string|max:255',
            'name_mother' => 'required|string|max:255',
            'date_birth' => 'required|date',
            'cpf' => 'required|string|unique:patients',
            'cns' => 'required|string|unique:patients',
            'zip_code' => 'required|string|max:8',
            'address' => 'required|string|max:255',
            'number' => 'required|numeric',
            'complement' => 'nullable|string|max:255',
            'district' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:2',
        ]);

        // create a new address
        $address = Address::create([
            'zip_code' => $validatedData['zip_code'],
            'address' => $validatedData['address'],
            'number' => $validatedData['number'],
            'complement' => $validatedData['complement'],
            'district' => $validatedData['district'],
            'city' => $validatedData['city'],
            'state' => $validatedData['state'],
        ]);
        
        // create a new patient with the corresponding address id
        $patient = new Patient;
        if ($request->hasFile('image_patient')) {
            $image = $request->file('image_patient');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = storage_path('app/public/images/patients');
            $image->move($destinationPath, $name);
            $patient->image_patient = $name;
        }
        $patient->name = $validatedData['name'];
        $patient->name_mother = $validatedData['name_mother'];
        $patient->date_birth = $validatedData['date_birth'];
        $patient->cpf = $validatedData['cpf'];
        $patient->cns = $validatedData['cns'];
        $patient->address()->associate($address);
        $patient->save();

        return response()->json([
            'success' => true,
            'data' => $patient,
        ]);
    }

    public function show($id)
    {
        // $patient = Patient::findOrFail($id);
        $patients = Patient::with('address')->get();

        return response()->json([
            'success' => true,
            'data' => $patients,
        ]);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
