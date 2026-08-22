<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentTimeline extends Model
{
    protected $table = 'investment_timeline';
    protected $fillable = ['value'];
    public $timestamps = false;
}
