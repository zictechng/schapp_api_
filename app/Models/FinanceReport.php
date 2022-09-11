<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceReport extends Model
{
    use HasFactory;
    protected $table = "finance_reports";
    protected $fillable = [
        'amt',
        'type',
        'qty',
        'nature',
        'disc',
        'expense',
        'addedby',
        'status',
        'fin_tid',
        'add_date',
        'approve_date',
        'close_date',
    ];
}