<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $table = "attendances";
    protected $fillable = [
        'atten_admin_no',
        'atten_stu_name',
        'atten_class',
        'atten_year',
        'atten_term',
        'atten_mark_date',
        'atten_submit_date',
        'atten_status',
        'atten_addeby',
        'atten_class_name',
        'atten_year_name',
        'atten_term_name',
        'atten_tid',
        'atten_date',
    ];
}