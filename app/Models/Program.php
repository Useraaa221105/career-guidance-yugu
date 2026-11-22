<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Program extends Model
{
    protected $fillable = [
        'institution_id', 'school_id', 'code', 'name', 'level', 'study_form', 'description',
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'program_category');
    }

    public function professions(): BelongsToMany
    {
        return $this->belongsToMany(Profession::class, 'program_profession');
    }
}
