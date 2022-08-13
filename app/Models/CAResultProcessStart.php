<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAResultProcessStart extends Model
{
    use HasFactory;
    protected $table = "c_a_result_process_starts";
    protected $fillable = [
        'year',
        'term',
        'class',
        'subject',
        'sch_category',
        'tid_code',
        'add_by',
        'status',
        'record_date',
    ];
}