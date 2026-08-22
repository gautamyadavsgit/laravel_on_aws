<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNetWorth extends Model
{
    protected $table = 'users_net_worth';
    protected $fillable = ['value'];
    public $timestamps = false;
}
