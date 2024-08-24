<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarRecommendationController extends Controller
{
    public function getUserPreferences(){

        //get owner id
        $user_Data = Auth()->user();

        $price_info = $user_Data->price_preference;
        $hp_info = $user_Data->hp_preference;
        $drive_type_info = $user_Data->drive_type_preference;

        //response
        return response()->json([$price_info, $hp_info, $drive_type_info]);

    }

    public function getAllCars(): \Illuminate\Http\JsonResponse
    {
        $cars = Car::get();

        //response
        return response()->json($cars);
    }

    public function getRecommendedCars(Request $request): JsonResponse
    {

        //get id's of all the owners
        $user_id = auth()->user();

        $request->validate([
            "hp" => "required"
        ]);

        $result = DB::table('cars')
            ->select('*', DB::raw('ABS(hp - :input) AS distance'))
            ->setBindings(['input' => $request->hp])
            ->orderBy('distance')
            ->get();

        //response
        return response()->json([

            "status"=>1,
            "data"=>$result

        ]);

/*
         if ($car = Car::where('id', $car_id)->exists()) {
            $car = Car::where('id', $car_id)->first();

            $comment = new Comment();

            $comment->user_id = auth()->user()->id;
            $comment->car_id = $car->id;
            $comment->body = $request->body;

            $comment->save();

            $comment->load("user");

            return response()->json([
                "status" => 1,
                "msg" => "comment saved !"

            ]);
        } else {
            return response()->json([
                "status" => false,
                "msg" => "no cars match !!"
            ]);
       }
*/


    }

}
