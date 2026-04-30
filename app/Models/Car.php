<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rental;

// Post a car part 

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'car_image',
        'date_owned',
        'brand',
        'model',
        'price',
        'rent_period',
        'transmission',
        'fuel_type',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $casts = [
        'created_at' => 'datetime',
        'is_rented' => 'boolean',
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}