<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StartPromotion extends Model
{
    use HasFactory;
    protected $table = "start_promotions";
    protected $fillable = [
        'stu_adm_number',
        'stu_name',
        'stu_class',
        'stu_next_class',
        'stu_status',
        'stu_tid',
        'stu_date',
        'stu_addby',
    ];
}