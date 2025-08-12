<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'capacity',
    ];

    /**
     * Get the bookings for the room.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
