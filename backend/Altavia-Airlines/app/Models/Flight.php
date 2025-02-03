<?php

namespace App\Models;

use App\Models\City;
use App\Models\User;
use App\Models\Airplane;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'price',
        'airplane_id',
    ];

    public function airplane(): BelongsTo {
        return $this->belongsTo(Airplane::class, 'airplane_id');
    }

    public function cities(): BelongsToMany {
        return $this->belongsToMany(City::class, 'city_flight');
    }

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class, 'flight_user');
    }
}
