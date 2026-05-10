<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rental_id',
        'car_id',
        'type',
        'title',
        'body',
        'url',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
