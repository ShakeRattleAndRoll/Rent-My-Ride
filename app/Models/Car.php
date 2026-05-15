<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Rental;
use App\Models\User;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'car_image',
        'date_owned',
        'brand',
        'model',
        'price',
        'rent_unit',
        'transmission',
        'fuel_type',
        'description',
        'approval_status',
        'is_available',
        'approved_at',
        'approved_by',
        'auto_accept',
        'auto_accept_priority',
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
        'price' => 'integer',
        'is_available' => 'boolean',
        'approved_at' => 'datetime',
        'auto_accept' => 'boolean',
    ];

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopePubliclyVisible($query)
    {
        return $query->approved()->where('is_available', true);
    }

    public function approve(User $admin): void
    {
        $this->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $admin->id,
        ]);
    }

    /* Helper: Occupied Status*/

    public function isOccupied()
    {
        return $this->rentals()
            ->where('status', 'accepted')->where('start_date', '<=', now())->where('end_date', '>=', now())
            ->exists();
    }
}
