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
        'ca_3',
        'ca_4',
        'ca_5',
        'ca_6',
        'addby',
        'res_status',
        'reg_date',
    ];
}