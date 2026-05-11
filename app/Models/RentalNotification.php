<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RentalNotification extends Model
{
    use HasFactory, SoftDeletes;

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
        'deleted_at' => 'datetime',
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
