<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyInterest extends Model
{
    use HasFactory;

    protected $table = 'property_interests';

    protected $fillable = [
        'user_id',
        'property_id',
        'status',
        'notes',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
