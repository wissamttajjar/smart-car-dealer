<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CarRecommendationController;
use App\Http\Controllers\api\CarsController;
use App\Http\Controllers\api\CommentController;
use App\Http\Controllers\api\PreferencesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\CarPricePredictionController;


//unauthenticated api functions

//unauthenticated user api functions
Route::post("register" , [AuthController::class , "register"]);
Route::post("login" , [AuthController::class , "login"]);
Route::get("search_Car/{brand}", [CarsController::class , "searchForCar"]);
Route::get("list_comments/{product_id}" , [CommentController::class , "list"]);
Route::get("get_all_cars" , [CarRecommendationController::class , "getAllCars"]);

//authenticated api functions
Route::group(["middleware" => ["auth:api"]] , function (){

    //authenticated user api functions
    Route::get("show-profile" , [AuthController::class , "showProfile"]);
    Route::post("logout" , [AuthController::class , "logout"]);
    Route::post("setPreference",[PreferencesController::class , "addPreferences"]);
    Route::get("get_prefs",[PreferencesController::class , "getPreferences"]);
    Route::post("update_prefs/{id}",[PreferencesController::class , "updatePreference"]);

    //authenticated user's cars api functions
    Route::post("add-Car" , [CarsController::class , "addCar"]);
    Route::post("update-Car/{id}" , [CarsController::class , "updateCar"]);
    Route::get("delete-Car/{id}" , [CarsController::class , "deleteCar"]);
    Route::get("list-User-Car" , [CarsController::class , "listUserCar"]);
    Route::get("get_pref's", [CarRecommendationController::class,"getUserPreferences"]);
    Route::get("get_car_by_id/{id}" , [CarsController::class, "getCarById"]);
    Route::get("get_recommended_cars" , [CarRecommendationController::class, "getRecommendedCars"]);
    Route::post("predict_price" , [CarPricePredictionController::class, "predict"]);



    //authenticated user's comments api functions
    Route::post("create_comment/{product_id}" , [CommentController::class , "create"]);

});
