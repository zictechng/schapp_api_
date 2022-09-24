<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetup extends Model
{
    use HasFactory;
    protected $table = "system_setups";
    protected $fillable = [
        'sch_name',
        'sch_name_short',
        'sch_phone',
        'sch_email',
        'sch_logo',
        'sch_banner',
        'sch_favicon',
        'sch_action',
        'app_state',
        'app_student_section',
        'app_staff_section',
        'app_admin_section',
        'addby',
        'add_date',
        'app_status',
    ];
}