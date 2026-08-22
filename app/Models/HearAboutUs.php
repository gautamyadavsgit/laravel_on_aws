<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HearAboutUs extends Model
{
    protected $table = 'hear_about_us';
    protected $fillable = ['value'];
    public $timestamps = false;
}
