<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StartGraduation extends Model
{
    use HasFactory;
    protected $table = "start_graduations";
    protected $fillable = [
        'gs_st_admin',
        'gs_st_name',
        'gs_class',
        'gs_year',
        'gs_status',
        'gs_added',
        'gs_date',

    ];
}