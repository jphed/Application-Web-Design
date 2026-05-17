<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'goal_id',
        'title',
        'due_date',
        'completed',
        'order',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'completed' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }
}
