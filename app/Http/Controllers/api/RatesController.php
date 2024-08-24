<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class RatesController extends Controller
{
    public function car_Rate($id): \Illuminate\Http\JsonResponse
    {
        // Get the cars from the database
        $cars = Car::find($id);

        // Find the rating record if it exists,
        // if it doesn't create a new one with no likes
        $rate = Rate::firstOrCreate(
            ['user_id' => auth()->user()->id, 'car_id' => $cars->id],
            ['user_id' => auth()->user()->id, 'car_id' => $cars->id, 'reacts' => 0],
        );

        // Declare an empty variable that will determine if
        // the cars is being "liked" or "disliked"
        $rateAction = '';

        // Determine if the cars has likes or not
        if ($rate->score > 0)
        {
            // The cars has at least 1 like
            $rateAction = 'dislike';

            // Decrement the likes by 1
            $rate->decrement('score', 1);
            $cars->decrement('reacts', 1);
        }
        else
        {
            // The cars has 0 likes
            $rateAction = 'like';

            // Increment the likes by 1
            $rate->incremen3t('score', 1);
            $cars->increment('reacts', 1);
        }

        // Determine if this was a new rate record
        if ($rate->wasRecentlyCreated)
        {
            // Override the rate action as "New table"
            $rateAction = 'New table';
        }

        // Return the goods
        return response()->json(['status' => true, 'msg' => $rateAction]);

    }
}
