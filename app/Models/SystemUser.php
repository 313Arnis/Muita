<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemUser extends Model
{
    protected $table = 'system_users';

    protected $fillable = [
        'external_id', 'username', 'full_name', 'role', 'active'
    ];
}