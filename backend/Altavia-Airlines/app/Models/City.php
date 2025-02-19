<?php

namespace App\Models;

use App\Models\Flight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function departures(): HasMany {
        return $this->hasMany(Flight::class, 'departure_id');
    }

    public function arrivals(): HasMany {
        return $this->hasMany(Flight::class, 'arrival_id');
    }
}
