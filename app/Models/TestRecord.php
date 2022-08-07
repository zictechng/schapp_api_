<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestRecord extends Model
{
    use HasFactory;
    protected $table = "test_records";
    protected $fillable = [
        'record_id',
        'item_name',
        'qty',
        'unit_price',
        'purch_price',
        'selling_price',
        'total',
        'addby',
        'rec_date',
        'rec_status',
    ];
}