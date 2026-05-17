<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'goal_id',
        'note',
        'progress_value',
        'logged_at',
    ];

    protected function casts(): array
    {
        return [
            'progress_value' => 'integer',
            'logged_at' => 'datetime',
        ];
    }

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }
}
