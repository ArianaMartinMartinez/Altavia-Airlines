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
        'departure_id',
        'arrival_id',
    ];

    public function airplane(): BelongsTo {
        return $this->belongsTo(Airplane::class, 'airplane_id');
    }

    public function departure(): BelongsTo {
        return $this->belongsTo(City::class, 'departure_id');
    }

    public function arrival(): BelongsTo {
        return $this->belongsTo(City::class, 'arrival_id');
    }

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class, 'flight_user');
    }
}
