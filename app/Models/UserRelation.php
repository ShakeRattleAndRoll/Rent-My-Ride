<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRelation extends Model
{
    protected $table = 'user_relations';

    protected $fillable = ['user_id', 'target_id', 'type'];

    public function target()
    {
        return $this->belongsTo(User::class, 'target_id');
    }
}