<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Graduation extends Model
{
    use HasFactory;
    protected $table = "graduations";
    protected $fillable = [
        'g_st_admin',
        'g_st_name',
        'g_class',
        'g_year',
        'g_status',
        'g_added',
        'g_date',
    ];
}