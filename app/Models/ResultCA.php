<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultCA extends Model
{
    use HasFactory;
    protected $table = "result_c_a_s";
    protected $fillable = [
        'st_admin_id',
        'st_name',
        'ca1',
        'ca2',
        'hrs_work',
        'hrs_earned',
        'ca_total',
        'rst_year',
        'rst_term',
        'rst_subject',
        'rst_category',
        'rst_class',
        'rst_tid',
        'rst_date',
        'rst_status',
        'rst_addby',
    ];
}