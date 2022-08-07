<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermModel extends Model
{
    use HasFactory;
    protected $table = "term_models";
    protected $fillable = [
        'term_name',
        'added_by',
        't_status',
        't_date',
        't_action',
    ];
}