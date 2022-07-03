<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dessert extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'calories', 'fat', 'carbs', 'protein','user_id'
    ];
    protected $hidden = [
        'user_id', 'deleted_at', 'updated_at'
    ];
    protected $casts = [
        'created_at' => 'date:d.m.Y'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
