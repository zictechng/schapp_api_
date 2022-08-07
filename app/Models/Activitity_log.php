<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activitity_log extends Model
{
    use HasFactory;
    protected $table = "activitity_logs";
    protected $fillable = [
        'm_username',
        'm_action',
        'm_status',
        'm_details',
        'm_date',
        'm_uid',
        'm_device_name',
        'm_broswer',
        'm_device_number',
        'm_location',
        'm_ip',
        'm_city',
        'm_record_id',
    ];
}