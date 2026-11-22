<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestSessionBlock extends Model
{
    protected $fillable = ['test_session_id', 'test_block_id', 'completed_at'];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function testSession(): BelongsTo
    {
        return $this->belongsTo(TestSession::class);
    }

    public function block(): BelongsTo
    {
        return $this->belongsTo(TestBlock::class, 'test_block_id');
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }
}
