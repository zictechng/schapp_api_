<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneratePin extends Model
{
    use HasFactory;
    protected $table = "generate_pins";
    protected $fillable = [
        'card_pin',
        'card_serial',
        'card_status',
        'card_usage_count',
        'card_usage_status',
        'card_date',
        'card_use_date',
        'card_use_username',
        'card_addedby',
        'card_tid',
    ];
}