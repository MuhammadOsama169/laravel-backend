<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    /** @use HasFactory<\Database\Factories\WorkspaceFactory> */
    use HasFactory;

    protected $fillable = ['name', 'setting', 'description'];

    protected $casts = ['setting' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'model_id')->where('model', 'Workspace');
    }
}
