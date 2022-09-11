<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSave extends Model
{
    use HasFactory;
    protected $table = "test_saves";
    protected $fillable = [
        'email',
        'state_details',
        'class_details',
        'message_details',
        'tran_code',
        'status',
        'reg_date'
    ];
}