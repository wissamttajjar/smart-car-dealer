<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CarPricePredictionController extends Controller
{
    public function predict(Request $request): \Illuminate\Http\JsonResponse
    {

        $request->validate([
            "make" => "required",
            "year" => "required",
            "engine_hp" => "required",
            "transmission_type" => "required",
            "driven_wheels" => "required",
            "popularity" => "required"
        ]);

        // Preprocess input data
        $input = $request -> all();

        // Send POST request to Python API
        $response = Http::post('http://127.0.0.1:5000/predict', $input);

        // Return the prediction result to the client
        return response()->json($response->json());

    }
}
