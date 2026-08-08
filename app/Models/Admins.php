<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Admins extends Model implements Authenticatable
{
    use AuthenticatableTrait;
    use HasFactory;

    public function properties(): HasMany
    {
        return $this->hasMany('\App\Models\PropertyModel', 'admin_id');
    }
}
