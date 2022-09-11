<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginStatus extends Model
{
    use HasFactory;
    protected $table = "login_statuses";
    protected $fillable = [
        'user_id',
        'user_name',
        'login_name',
        'login_date',
        'login_nature',
        'login_uid',
        'login_status',
        'login_role',
        'logg_action',
        'logout_date'
    ];
}