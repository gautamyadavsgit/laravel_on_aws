<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PropertyDocument extends Model
{
    use HasFactory;

    protected $table = 'properties_document';

    protected $fillable = [
        'property_id',
        'document_key',
        'document_value',
    ];

    public function getUrlAttribute(): string
    {
        return Storage::url($this->document_value);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
