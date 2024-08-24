<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ["user_id" , "car_id" , "body"];

    public $appends=['human_readable_created_at'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getHumanReadableCreatedAtAttribute(){
        return $this->created_at->diffForHumans();
    }
}
