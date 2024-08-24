<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Preference;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\User;
use App\Models\Image;

class CarsController extends Controller
{

    //addCar method -> post ->in middleware
    public function addCar(Request $request): JsonResponse
    {
        //validation
        $request->validate([

            "brand" => "required",
            "year" => "required",
            "price" => "required",
            "drive_type" => "required",
            "hp" => "required",
            "auto_manual" => "required",
            "image_1" => "required|image|mimes:jpeg,png,jpg",
            "image_2" => "required|image|mimes:jpeg,png,jpg",
            "image_3" => "required|image|mimes:jpeg,png,jpg",
        ]);

        $newCar = new Car();

        $newCar->user_id = auth()->user()->id;
        $newCar->brand = $request->brand;
        $newCar->year = $request->year;
        $newCar->price = $request->price;
        $newCar->drive_type = $request->drive_type;
        $newCar->hp = $request->hp;
        $newCar->auto_manual = $request->auto_manual;

        $imageName = rand() . '.' . $request->image_1->getClientOriginalExtension();
        $request->image_1->move(public_path('uploads'), $imageName);
        $path = "public/uploads/$imageName";
        $newCar->image_1 = $path;

        $imageName = rand() . '.' . $request->image_2->getClientOriginalExtension();
        $request->image_2->move(public_path('uploads'), $imageName);
        $path = "public/uploads/$imageName";
        $newCar->image_2 = $path;

        $imageName = rand() . '.' . $request->image_3->getClientOriginalExtension();
        $request->image_3->move(public_path('uploads'), $imageName);
        $path = "public/uploads/$imageName";
        $newCar->image_3 = $path;

        $newCar->save();

        //response
        return response()->json([

            "status"=> 1,
            "msg"=>"Car added successfully !"
        ]);
    }

    //updateCar method -> post ->in middleware
    public function updateCar(Request $request , $car_id): JsonResponse
    {
        //get owner id
        $user_id = auth()->user()->id;

        //check if car exists
        if(Car::where([
            "user_id" => $user_id ,
            "id" => $car_id
        ]) -> exists()){

            //get car with matching id
            $car = Car::find($car_id);

            //update car data
            $car->brand = isset($request->brand) ? $request->brand : $car->brand;
            $car->year = isset($request->year) ? $request->year : $car->year;
            $car->price = isset($request->price) ? $request->price : $car->price;
            $car->drive_type = isset($request->drive_type) ? $request->drive_type : $car->drive_type;
            $car->hp = isset($request->hp) ? $request->hp : $car->hp;
            $car->auto_manual = isset($request->auto_manual) ? $request->auto_manual : $car->auto_manual;

            $car->save();

            return response()->json([

                "status"=>1,
                "msg"=>" car updated !! ",

            ]);
        }else{

            return response()->json([

                "status"=>false ,
                "msg"=>"owner car doesn't exist "

            ]);
        }

    }

    //delete method -> get ->in middleware
    public function deleteCar($car_id): JsonResponse
    {
        $user_id = auth()->user()->id;

        if(Car::where([
            "user_id" => $user_id ,
            "id" => $car_id
        ]) -> exists()){

            $car = Car::find($car_id);

            $car->delete();

            return response()->json([

                "status"=>1,
                "msg"=>" car deleted !! ",

            ]);
        }else{

            //response if it doesn't exist
            return response()->json([

                "status"=>false ,
                "msg"=>"owner car doesn't exist "

            ]);
        }
    }

    public function getCarById($car_id)
    {
        //get id's of all the owners
        $user_id = auth()->user();

        //find id's that match the preference
        $car = Car::find($user_id);

        //response
        return response()->json([

            "status"=>1,
            "data"=>$car

        ]);
    }

    //listUserCar method -> get ->in middleware
    public function listUserCar(): JsonResponse
    {
        //get id's of all the owners
        $user_id = auth()->user()->id;

        //find id's that match the cars
        $cars = User::find($user_id)->cars;

        //response
        return response()->json([

            "status"=>1,
            "msg"=>"User's cars ",
            "data"=>$cars

        ]);
    }

    //searchForCar method -> get ->out of middleware
    public function searchForCar($brand)
    {
        if(Car::where('brand', 'like', "%{$brand}%")
            ->exists()){
            return Car::where('brand', 'like', "%{$brand}%")->get();

        }else{

            return response()->json([
                "status" => false ,
                "msg" => "car doesn't exist ..."
            ]);

        }


    }

}
