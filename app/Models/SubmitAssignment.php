<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmitAssignment extends Model
{
    use HasFactory;
    protected $table = "submit_assignments";
    protected $fillable = [
        'assign_id',
        'student_id',
        'teacher_id',
        'assign_code',
        'assign_file_name',
        'assign_message',
        'assign_scores',
        'assign_remark',
        'assign_status',
        'assign_submit_date',
        'assign_updated_date',
        'assign_file_path',
        'assign_submit_code',
    ];
    protected $with = ['student_name', 'staffName'];  // this will pass it to javascript, jquery or any other programming platform outside laravel
    public function student_name()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id'); // this is for normal laravel format
    }

    public function staffName()
    {
        return $this->belongsTo(Staff::class, 'teacher_id', 'id'); // this is for normal laravel format
    }
}