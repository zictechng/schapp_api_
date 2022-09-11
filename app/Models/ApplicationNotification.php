<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationNotification extends Model
{
    use HasFactory;
    protected $table = "application_notifications";
    protected $fillable = [
        'title',
        'sub_title',
        'sub_body',
        'body_message',
        'feature',
        'feature_image',
        'feature_thumbnail',
        'belong_to',
        'action_state',
        'status',
        'added_by',
        'add_date',
    ];
}