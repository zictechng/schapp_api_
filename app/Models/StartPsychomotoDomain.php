<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StartPsychomotoDomain extends Model
{
    use HasFactory;
    protected $table = "start_psychomoto_domains";
    protected $fillable = [
        'saff_year',
        'saff_term',
        'saff_class',
        'saff_status',
        'saff_tid',
        'saff_addby',
        'saff_date',
    ];
}