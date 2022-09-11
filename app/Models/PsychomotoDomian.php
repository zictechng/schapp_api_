<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsychomotoDomian extends Model
{
    use HasFactory;
    protected $table = "psychomoto_domians";
    protected $fillable = [
        'effectiveness',
        'neatness_score',
        'craft_score',
        'punctuality_score',
        'sport_score',
        'aff_year',
        'aff_term',
        'aff_class',
        'aff_admin_number',
        'aff_student_name',
        'aff_addedby',
        'aff_tid',
        'aff_date',
        'aff_status',
    ];
}