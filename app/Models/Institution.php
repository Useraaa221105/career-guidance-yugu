<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends Model
{
    protected $fillable = ['name', 'short_name', 'city', 'type', 'description'];

    public function schools(): HasMany
    {
        return $this->hasMany(School::class);
    }

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }
}
