<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function create(Request $request, $car_id): JsonResponse
    {

        if ($car = Car::where('id', $car_id)->exists()) {
            $car = Car::where('id', $car_id)->first();
            $request->validate([

                "body" => "required"

            ]);

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
    }

    public function list(Request $request, $car_id): JsonResponse
    {
        if ($car = Car::where('id', $car_id)->exists()) {

            $car = car::where('id', $car_id)->first();
            $per_page = 5;
            $comments = Comment::with(["user"])->where("user_id", $car_id)->orderBy("id", "desc")->paginate($per_page);

            return response()->json([
                "status" => 1,
                "msg" => "comments found !",
                "data" => $comments
            ]);
        } else {
            return response()->json([
                "status" => false,
                "msg" => "no cars match !!"
            ]);
        }
    }
}
