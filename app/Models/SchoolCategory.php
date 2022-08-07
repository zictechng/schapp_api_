<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolCategory extends Model
{
    use HasFactory;
    protected $table = "school_categories";
    protected $fillable = [
        'sc_name',
        'sc_add_by',
        'sc_status',
        'sc_date',
        'sc_action',
    ];
}