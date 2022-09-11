<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;
    protected $table = "assignments";
    protected $fillable = [
        'assign_title',
        'assign_sub_title',
        'assign_body',
        'assign_class',
        'add_subject',
        'assign_file',
        'assign_type',
        'assign_status',
        'addby',
        'addby_user_id',
        'assign_class_id',
        'assign_submission_date',
        'assign_date',
        'assign_tid',
    ];
}