<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CepController extends Controller
{
    public function consult($cep)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get("https://viacep.com.br/ws/{$cep}/json/");
        $json = json_decode($response->getBody());

        return response()->json($json);
    }
}
