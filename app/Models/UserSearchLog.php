<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'sort_by',
        'filters_payload',
        'results_count',
    ];

    protected $casts = [
        'min_price' => 'integer',
        'max_price' => 'integer',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'filters_payload' => 'array',
        'results_count' => 'integer',
    ];
}
