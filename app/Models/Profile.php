<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class profile extends Model
{
    protected $fillable = ['user_id'];
    /** @use HasFactory<\Database\Factories\ProfileFactory> */
    use HasFactory;
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
