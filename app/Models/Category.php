<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'color', 'icon'];

    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'program_category');
    }

    public function answers(): BelongsToMany
    {
        return $this->belongsToMany(Answer::class, 'answer_category')->withPivot('points');
    }
}
