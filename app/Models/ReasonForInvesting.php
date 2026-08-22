<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReasonForInvesting extends Model
{
    protected $table = 'reason_for_investing';
    protected $fillable = ['value'];
    public $timestamps = false;
}
