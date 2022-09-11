<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentComment extends Model
{
    use HasFactory;
    protected $table = "student_comments";
    protected $fillable = [
        'comm_stu_number',
        'comm_stu_name',
        'comm_class',
        'comm_year',
        'comm_term',
        'comm_comment',
        'comm_prin_comment',
        'comm_status',
        'comm_addby',
        'comm_date',
        'comm_tid',
    ];
}