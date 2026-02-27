<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'address',
        'nationality',
        'gender',
        'id_type',
        'id_number',
        'vip',
        'blacklisted',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'vip' => 'boolean',
        'blacklisted' => 'boolean',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function documents()
    {
        return $this->hasMany(GuestDocument::class);
    }

}
