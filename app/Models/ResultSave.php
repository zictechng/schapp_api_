<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultSave extends Model
{
    use HasFactory;
    protected $table = "result_saves";
    protected $fillable = [
        'admin_number',
        'ca_1',
        'ca_2',
        'ca_total',
        'exam_score',
        'total',
        'year',
        'term',
        'class',
        'subject',
        'record_id',
        'addby',
        'res_status',
        'reg_date',
    ];
}