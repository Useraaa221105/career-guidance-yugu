<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestBlock extends Model
{
    protected $fillable = ['slug', 'name', 'description', 'icon', 'estimated_minutes', 'order_index'];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'block_id')->orderBy('order_index');
    }

    public function sessionBlocks(): HasMany
    {
        return $this->hasMany(TestSessionBlock::class);
    }
}
