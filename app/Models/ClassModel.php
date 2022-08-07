<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;
    protected $table = "class_models";
    protected $fillable = [
        'class_name',
        'added_by',
        'status',
        'action',
        'record_date',
    ];
}