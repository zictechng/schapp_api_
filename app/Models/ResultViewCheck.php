<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultViewCheck extends Model
{
    use HasFactory;
    protected $table = "result_view_checks";
    protected $fillable = [
        'year',
        'term',
        'class',
        'subject',
        'category',
        'status',
        'view_by',
        'view_code',
        'reg_date',

    ];
    protected $with = ['class_name'];  // this will pass it to javascript, jquery or any other programming platform outside laravel
    public function class_name()
    {
        return $this->belongsTo(ClassModel::class, 'class', 'id'); // this is for normal laravel format
    }
    public function term_subject()
    {
        return $this->belongsTo(Subject::class, 'subject', 'id'); // this is for normal laravel format
    }
    public function sch_term()
    {
        return $this->belongsTo(TermModel::class, 'term', 'id'); // this is for normal laravel format
    }
    public function sch_year()
    {
        return $this->belongsTo(AcademicSession::class, 'year', 'id'); // this is for normal laravel format
    }
}