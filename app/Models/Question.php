<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = ['block_id', 'text', 'order_index', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function block(): BelongsTo
    {
        return $this->belongsTo(TestBlock::class, 'block_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class)->orderBy('order_index');
    }

    public function activeAnswers(): HasMany
    {
        return $this->hasMany(Answer::class)->where('is_active', true)->orderBy('order_index');
    }
}
