<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'deadline',
        'status',
        'progress',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'progress' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class)->orderBy('order');
    }

    public function progressLogs(): HasMany
    {
        return $this->hasMany(ProgressLog::class)->orderByDesc('logged_at');
    }

    public static function categories(): array
    {
        return ['salud', 'educación', 'finanzas', 'personal', 'fitness', 'lectura', 'aprendizaje'];
    }
}
