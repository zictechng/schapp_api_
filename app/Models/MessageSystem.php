<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageSystem extends Model
{
    use HasFactory;
    protected $table = "message_systems";
    protected $fillable = [
        'receiver_user_id',
        'sender_user_id',
        'mes_nature',
        'mes_title',
        'mes_body',
        'mes_sender_name',
        'mes_receiver_email',
        'mes_file',
        'mes_status',
        'mes_delete_uid',
        'mes_receiver_status',
        'mes_send_date',
        'mes_delete_date',
        'mes_action',
        'mes_tid',
    ];
}