<?php

namespace ViicSlen\TrackableTasks\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ViicSlen\TrackableTasks\Enums\ExceptionSeverity;

class TrackedException extends Model
{
    use HasFactory;

    protected $fillable = [
        'severity',
        'message',
    ];

    protected $attributes = [
        'severity' => ExceptionSeverity::WARNING,
    ];

    protected $casts = [
        'severity' => ExceptionSeverity::class,
    ];

    protected $touches = [
        'task',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(TrackedTask::class);
    }
}
