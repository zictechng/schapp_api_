<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultTable extends Model
{
    use HasFactory;
    protected $table = "result_tables";
    protected $fillable = [
        'admin_number',
        'academic_year',
        'academy_term',
        'subject',
        'class',
        'school_category',
        'first_ca',
        'second_ca',
        'earn_hrs',
        'hrs_work',
        'tca_score',
        'exam_scores',
        'total_scores',
        'grade',
        'remark',
        'position',
        'average_scores',
        'class_total',
        'tid_code',
        'username',
        'student_name',
        'result_date',
        'result_action',
        'result_status',
        'result_lowest',
        'result_highest',
        'result_action_date',
    ];
    protected $with = ['class_name', 'sch_year', 'sch_category', 'sch_term', 'subject'];  // this will pass it to javascript, jquery or any other programming platform outside laravel
    public function class_name()
    {
        return $this->belongsTo(ClassModel::class, 'class', 'id'); // this is for normal laravel format
    }
    public function sch_year()
    {
        return $this->belongsTo(AcademicSession::class, 'academic_year', 'id'); // this is for normal laravel format
    }
    public function sch_term()
    {
        return $this->belongsTo(TermModel::class, 'academy_term', 'id'); // this is for normal laravel format
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject', 'id'); // this is for normal laravel format
    }
    public function sch_category()
    {
        return $this->belongsTo(SchoolCategory::class, 'school_category', 'id'); // this is for normal laravel format
    }
}