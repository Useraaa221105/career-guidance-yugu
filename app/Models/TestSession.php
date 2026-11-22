<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class TestSession extends Model
{
    protected $fillable = [
        'user_id', 'token', 'completed_at',
        'city', 'education', 'study_pace', 'study_form_pref',
        'name', 'age', 'gender', 'institution_type',
        'budget', 'relocation', 'dormitory', 'ege_status',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sessionAnswers(): HasMany
    {
        return $this->hasMany(TestSessionAnswer::class);
    }

    public function sessionBlocks(): HasMany
    {
        return $this->hasMany(TestSessionBlock::class);
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function completedBlockSlugs(): array
    {
        return $this->sessionBlocks()
            ->whereNotNull('completed_at')
            ->with('block')
            ->get()
            ->pluck('block.slug')
            ->toArray();
    }

    public function allBlocksCompleted(Collection $blocks): bool
    {
        $completedSlugs = $this->completedBlockSlugs();
        foreach ($blocks as $block) {
            if ($block->slug === 'anketa') continue; // анкета — не тест
            if (!in_array($block->slug, $completedSlugs)) return false;
        }
        return true;
    }
}
