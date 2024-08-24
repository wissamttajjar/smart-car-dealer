<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Preference;
use App\Models\User;
use Illuminate\Http\Request;

class PreferencesController extends Controller
{
    public function addPreferences(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
           "price_preference" => "required",
           "hp_preference" => "required",
           "drive_type_preference" => "required"
        ]);

        $preference = new Preference();

        $preference->user_id = auth()->user()->id;
        $preference->price_preference = $request->price_preference;
        $preference->hp_preference = $request->hp_preference;
        $preference->drive_type_preference = $request->drive_type_preference;

        $preference->save();

        //response
        return response()->json([

            "status"=> 1,
            "msg"=>"Preference added successfully !"
        ]);
    }

    public function getPreferences(): \Illuminate\Http\JsonResponse
    {
        //get id's of all the owners
        $user_id = auth()->user();

        //find id's that match the preference
        $preference = Preference::find($user_id);

        //response
        return response()->json([

            "status"=>1,
            "msg"=>"User's preference ",
            "data"=>$preference

        ]);
    }

    public function updatePreference(Request $request, $preference_id): \Illuminate\Http\JsonResponse
    {
        //get owner id
        $user_id = auth()->user()->id;

        //check if preference exists
        if(Preference::where([
            "user_id" => $user_id ,
            "id" => $preference_id
        ]) -> exists()){

            //get preference with matching id
            $preference = Preference::find($preference_id);

            $preference->price_preference = $request->price_preference;
            $preference->hp_preference = $request->hp_preference;
            $preference->drive_type_preference = $request->drive_type_preference;

            $preference->save();

            return response()->json([

                "status"=>1,
                "msg"=>" Preference updated !! ",

            ]);
        }else{

            return response()->json([

                "status"=>false ,
                "msg"=>"owner car doesn't exist "

            ]);
        }


    }

}
