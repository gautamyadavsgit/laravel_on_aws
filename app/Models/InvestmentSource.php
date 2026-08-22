<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentSource extends Model
{
    protected $table = 'investment_sources';
    protected $fillable = ['value'];
    public $timestamps = false;
}
