<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentSession extends Model
{
    use HasFactory;
    protected $table = "current_sessions";
    protected $fillable = [
        'running_session',
        'session_status',
        'session_addedby',
        'session_date',
    ];
    /* this will create a database relationship between Current Session
    /* and School year table */

    protected $with = ['schoolyear'];  // this will pass it to javascript, jquery or any other programming platform outside laravel
    public function schoolyear()
    {
        return $this->belongsTo(AcademicSession::class, 'running_session', 'id'); // this is for normal laravel format
    }
    /* category_id is the id in the product table, id is the id in category table
    /* so we are much both together here to fetch the actual name */
}