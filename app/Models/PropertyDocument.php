<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PropertyDocument extends Model
{
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
}
