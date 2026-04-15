<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'address',
        'nationality',
        'gender',
        'company_id',
        'travel_agent_id',
        'loyalty_id',
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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function travelAgent()
    {
        return $this->belongsTo(TravelAgent::class);
    }

    public function loyalty()
    {
        return $this->belongsTo(Loyalty::class);
    }

    public function blacklist()
    {
        return $this->hasOne(Blacklist::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function reservationsAsGuest()
    {
        return $this->belongsToMany(Reservation::class, 'reservation_guests')
            ->withPivot(['is_primary'])
            ->withTimestamps();
    }

    public function documents()
    {
        return $this->hasMany(GuestDocument::class);
    }

}
