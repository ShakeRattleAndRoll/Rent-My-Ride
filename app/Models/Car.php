<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rental;
use App\Models\User;

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

    /*
    Relationships
    */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    /*
     Casts (IMPORTANT FIX)
    */

    protected $casts = [
        'created_at' => 'datetime',
        'date_owned' => 'date',
    ];

    /* Helper: Occupied Status*/

    public function isOccupied()
    {
        return $this->rentals()
            ->where('status', 'accepted')
            ->exists();
    }
}