<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Answer extends Model
{
    protected $fillable = ['question_id', 'text', 'order_index', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'answer_category')->withPivot('points');
    }
}
