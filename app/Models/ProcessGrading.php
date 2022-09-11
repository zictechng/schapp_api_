<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessGrading extends Model
{
    use HasFactory;
    protected $table = "process_gradings";
    protected $fillable = [
        'stu_admin_no',
        'stu_name',
        'g_class',
        'g_term',
        'g_year',
        'g_category',
        'total_ca',
        'g_exam',
        'g_code',
        'total_score',
        'g_position',
        'g_addby',
        'g_date',
        'g_status',
    ];
    protected $with = ['className', 'sch_year', 'sch_category', 'sch_term'];  // this will pass it to javascript, jquery or any other programming platform outside laravel
    public function className()
    {
        return $this->belongsTo(ClassModel::class, 'g_class', 'id'); // this is for normal laravel format
    }
    public function sch_year()
    {
        return $this->belongsTo(AcademicSession::class, 'g_year', 'id'); // this is for normal laravel format
    }
    public function sch_term()
    {
        return $this->belongsTo(TermModel::class, 'g_term', 'id'); // this is for normal laravel format
    }

    public function sch_category()
    {
        return $this->belongsTo(SchoolCategory::class, 'g_category', 'id'); // this is for normal laravel format
    }
    public function st_name()
    {
        return $this->belongsTo(Student::class, 'stu_admin_no', 'st_admin_number'); // this is for normal laravel format
    }
}