<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccreditationStatus extends Model
{
    protected $table = 'accreditation_status';
    protected $fillable = ['value'];
    public $timestamps = false;
}
