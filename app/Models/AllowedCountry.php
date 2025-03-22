<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllowedCountry extends Model
{
    /** @use HasFactory<\Database\Factories\AllowedCountryFactory> */
    use HasFactory,HasUuids;

    protected $fillable =['allowed_countries','tags'];
    protected $casts = ['allowed_countries' => 'array','tags' => 'array' ];

    public function workspace(){
        return $this->belongsTo(Workspace::class);
    }
}
