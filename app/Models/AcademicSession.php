<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSession extends Model
{
    use HasFactory;

    protected $table = "academic_sessions";
    protected $fillable = [
        'academic_name',
        'add_by',
        'a_status',
        'a_date',
        'a_action',
    ];
}