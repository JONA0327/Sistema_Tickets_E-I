<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaintenanceTimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'capacity',
        'is_open',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_open' => 'boolean',
    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'maintenance_time_slot_id');
    }

    public function getAvailableCapacityAttribute(): int
    {
        return max(0, $this->capacity - $this->tickets()->count());
    }

    public function getDisplayTimeAttribute(): string
    {
        $start = $this->start_time ? $this->start_time->format('H:i') : '';
        $end = $this->end_time ? $this->end_time->format('H:i') : null;

        return $end ? "$start - $end" : $start;
    }
}
