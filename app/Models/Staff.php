<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;
    protected $table = "staff";
    protected $fillable = [
        'surname',
        'other_name',
        'sex',
        'dob',
        'state',
        'country',
        'email',
        'phone',
        'staff_id',
        'school_category',
        'qualification',
        'acct_username',
        'staff_password',
        'class',
        'home_address',
        'staff_image',
        'addby',
        'acct_status',
        'acct_action',
        'reg_date',
        'staff_level',

    ];
    protected $with = ['class_name', 'session_year', 'sch_category'];  // this will pass it to javascript, jquery or any other programming platform outside laravel
    public function class_name()
    {
        return $this->belongsTo(ClassModel::class, 'class', 'id'); // this is for normal laravel format
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