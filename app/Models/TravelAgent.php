<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TravelAgent extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'commission_percentage',
        'address',
    ];

    protected $casts = [
        'commission_percentage' => 'decimal:2',
    ];

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }
}
