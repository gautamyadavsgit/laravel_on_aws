<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentGoal extends Model
{
    protected $table = 'investment_goals';
    protected $fillable = ['value'];
    public $timestamps = false;
}
