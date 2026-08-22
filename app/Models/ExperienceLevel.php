<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExperienceLevel extends Model
{
    protected $table = 'experiance_level';
    protected $fillable = ['value'];
    public $timestamps = false;
}
