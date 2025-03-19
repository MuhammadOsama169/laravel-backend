<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = ['user_id', 'model', 'model_id', 'changes'];

    protected $casts = [
        'changes' => 'array', // Ensure changes are stored as an array
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
