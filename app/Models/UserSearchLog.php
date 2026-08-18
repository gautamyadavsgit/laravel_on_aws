<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSearchLog extends Model
{
    use HasFactory;

    protected $table = 'user_search_logs';

    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'keyword',
        'location',
        'min_price',
        'max_price',
        'property_type',
        'bedrooms',
        'bathrooms',
        'min_cap_rate',
        'is_1031',
        'sort_by',
        'filters_payload',
        'results_count',
    ];

    protected $casts = [
        'min_price' => 'integer',
        'max_price' => 'integer',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'min_cap_rate' => 'decimal:2',
        'is_1031' => 'boolean',
        'filters_payload' => 'array',
        'results_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
