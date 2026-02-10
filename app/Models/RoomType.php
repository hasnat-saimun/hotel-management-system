<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Amenity;
use App\Models\Room;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'capacity_adults', 'capacity_children', 'base_price', 'description', 'is_active'
    ];

    protected $casts = [
        'capacity_adults' => 'integer',
        'capacity_children' => 'integer',
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (RoomType $type) {
            if (!empty($type->slug)) {
                return;
            }

            $base = Str::slug($type->name ?? '');
            $slug = $base;
            $counter = 1;

            while (static::where('slug', $slug)
                ->when($type->exists, function ($q) use ($type) {
                    return $q->where('id', '!=', $type->id);
                })
                ->exists()) {
                $slug = $base . '-' . $counter;
                $counter++;
            }

            $type->slug = $slug;
        });
    }

    public function amenities()
    {
        return $this->belongsToMany(
            Amenity::class,
            'amenity_room',
            'room_type_id',
            'amenity_id'
        )->withTimestamps();
    }

    public function room()
    {
        return $this->hasMany(Room::class, 'room_type_id')->where('is_active', true);
    }
}
