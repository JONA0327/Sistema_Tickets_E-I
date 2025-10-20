<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class MaintenanceSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'capacity',
        'booked_count',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'is_active' => 'boolean',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(MaintenanceBooking::class);
    }

    public function getStartDateTimeAttribute(): Carbon
    {
        $start = $this->start_time instanceof Carbon ? $this->start_time->format('H:i:s') : $this->start_time;
        return Carbon::parse($this->date->format('Y-m-d').' '.$start);
    }

    public function getEndDateTimeAttribute(): Carbon
    {
        $end = $this->end_time instanceof Carbon ? $this->end_time->format('H:i:s') : $this->end_time;
        return Carbon::parse($this->date->format('Y-m-d').' '.$end);
    }

    public function getAvailableCapacityAttribute(): int
    {
        return max(0, $this->capacity - $this->booked_count);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
