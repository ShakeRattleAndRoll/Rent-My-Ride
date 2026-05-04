<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'car_id',
        'status',
        'start_date',
        'end_date',
        'days',
        'rent_unit',
        'total_price',
        'hidden_by_renter',
        'hidden_by_owner',
        'snap_brand',
        'snap_model',
        'snap_car_image',
        'snap_price',
        'snap_rent_unit',
        'snap_fuel_type',
        'snap_transmission',
        'snap_date_owned',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}