<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StartAttendance extends Model
{
    use HasFactory;
    protected $table = "start_attendances";
    protected $fillable = [
        'sta_admin_no',
        'sta_stu_name',
        'sta_class',
        'sta_year',
        'sta_term',
        'sta_mark_date',
        'sta_submit_date',
        'sta_status',
        'sta_addeby',
        'sta_class_name',
        'sta_year_name',
        'sta_term_name',
        'sta_tid',
        'sta_date',
    ];
}