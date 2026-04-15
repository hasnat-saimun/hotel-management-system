<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loyalty extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'level_name',
        'discount_percentage',
        'points_required',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'points_required' => 'integer',
    ];

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }
}
