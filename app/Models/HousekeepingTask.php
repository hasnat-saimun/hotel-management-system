<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HousekeepingTask extends Model
{
    protected $fillable = [
        'room_id',
        'task_date',
        'status',
        'priority',
        'assigned_to',
        'notes',
        'completed_at',
    ];

    protected $casts = [
        'task_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
