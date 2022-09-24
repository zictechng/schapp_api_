<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $table = "students";
    protected $fillable = [
        'surname',
        'other_name',
        'sex',
        'dob',
        'st_age',
        'state',
        'st_password',
        'lga',
        'country',
        'last_sch_attend',
        'last_class_attend',
        'class_apply',
        'schooling_type',
        'academic_year',
        'school_category',
        'st_admin_number',
        'st_image',
        'guardia_name',
        'guardia_email',
        'guardia_number',
        'guardia_address',
        'staff_zone',
        'staff_depart',
        'staff_rank',
        'health_issue',
        'reg_date',
        'acct_status',
        'staff_file_no',
        'acct_action',
    ];

    protected $with = ['class_name', 'student_name', 'session_year', 'sch_category'];  // this will pass it to javascript, jquery or any other programming platform outside laravel
    public function class_name()
    {
        return $this->belongsTo(ClassModel::class, 'class_apply', 'id'); // this is for normal laravel format
    }
    public function student_name()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id'); // this is for normal laravel format
    }
    public function session_year()
    {
        return $this->belongsTo(AcademicSession::class, 'academic_year', 'id'); // this is for normal laravel format
    }

    public function sch_category()
    {
        return $this->belongsTo(SchoolCategory::class, 'school_category', 'id'); // this is for normal laravel format
    }
}