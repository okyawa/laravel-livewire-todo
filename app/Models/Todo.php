<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'completed',
        'completed_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    /**
     * スコープ: 未完了のタスク
     */
    public function scopePending($query)
    {
        return $query->where('completed', false);
    }

    /**
     * スコープ: 完了済みのタスク
     */
    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }
}
