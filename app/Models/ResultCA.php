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
    protected $with = ['class_name', 'sch_year', 'sch_category', 'sch_term', 'term_subject'];  // this will pass it to javascript, jquery or any other programming platform outside laravel
    public function class_name()
    {
        return $this->belongsTo(ClassModel::class, 'rst_class', 'id'); // this is for normal laravel format
    }
    public function sch_year()
    {
        return $this->belongsTo(AcademicSession::class, 'rst_year', 'id'); // this is for normal laravel format
    }
    public function sch_term()
    {
        return $this->belongsTo(TermModel::class, 'rst_term', 'id'); // this is for normal laravel format
    }
    public function term_subject()
    {
        return $this->belongsTo(Subject::class, 'rst_subject', 'id'); // this is for normal laravel format
    }
    public function sch_category()
    {
        return $this->belongsTo(SchoolCategory::class, 'rst_category', 'id'); // this is for normal laravel format
    }
    public function st_name()
    {
        return $this->belongsTo(Student::class, 'st_admin_id', 'st_admin_number'); // this is for normal laravel format
    }
}