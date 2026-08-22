<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentTimelength extends Model
{
    protected $table = 'investment_timelength';
    protected $fillable = ['value'];
    public $timestamps = false;
}
