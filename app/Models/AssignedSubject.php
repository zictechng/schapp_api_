<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedSubject extends Model
{
    use HasFactory;
    protected $table = "assigned_subjects";
    protected $fillable = [
        'sub_teacher_id',
        'sub_subject_id',
        'sub_teacher_name',
        'sub_subject_name',
        'sub_status',
        'sub_tid',
        'sub_addby',
        'sub_date',
    ];
}