<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPosition extends Model
{
    use HasFactory;
    protected $table = "student_positions";
    protected $fillable = [
        'sch_year',
        'sch_term',
        'sch_class',
        'sch_category',
        'stu_admin_number',
        'tca_score',
        'exam_score',
        'total_score',
        'class_total',
        'user_code',
        'position',
        'add_by',
        'student_name',
        'p_date',
        'p_status'
    ];
    protected $with = ['className', 'schYear', 'schTerm'];  // this will pass it to javascript, jquery or any other programming platform outside laravel
    public function className()
    {
        return $this->belongsTo(ClassModel::class, 'sch_class', 'id'); // this is for normal laravel format
    }
    public function schYear()
    {
        return $this->belongsTo(AcademicSession::class, 'sch_year', 'id'); // this is for normal laravel format
    }
    public function schTerm()
    {
        return $this->belongsTo(TermModel::class, 'sch_term', 'id'); // this is for normal laravel format
    }
    public function term_subject()
    {
        return $this->belongsTo(Subject::class, 'subject', 'id'); // this is for normal laravel format
    }
    public function sch_category()
    {
        return $this->belongsTo(SchoolCategory::class, 'school_category', 'id'); // this is for normal laravel format
    }
    public function st_name()
    {
        return $this->belongsTo(Student::class, 'admin_number', 'st_admin_number'); // this is for normal laravel format
    }
}