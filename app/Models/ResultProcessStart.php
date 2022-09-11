<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultProcessStart extends Model
{
    use HasFactory;
    protected $table = "result_process_starts";
    protected $fillable = [
        'school_year',
        'school_term',
        'class',
        'school_category',
        'subject',
        'r_tid',
        'addby',
        'addby_id',
        'r_status',
        'r_date',
    ];
}