<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    use HasFactory;
    protected $table = "admin_users";
    protected $fillable = [
        'first_name',
        'other_name',
        'phone',
        'email',
        'user_name',
        'access_level',
        'sex',
        'acct_status',
        'acct_action',
        'password',
        'addby',
        'reg_date',
    ];
}