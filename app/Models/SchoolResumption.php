<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolResumption extends Model
{
    use HasFactory;
    protected $table = "school_resumptions";
    protected $fillable = [
        'start_date',
        'close_date',
        'next_resumption',
        'school_year',
        'school_term',
        'added_by',
        'status',
        'add_date',
    ];

    /* this will create a database relationship between category
    /* and product table */

    protected $with = ['schoolyear', 'schoolterm'];  // this will pass it to javascript, jquery or any other programming platform outside laravel
    public function schoolyear()
    {
        return $this->belongsTo(AcademicSession::class, 'school_year', 'id'); // this is for normal laravel format
    }
    /* category_id is the id in the product table, id is the id in category table
    /* so we are much both together here to fetch the actual name */

    public function schoolterm()
    {
        return $this->belongsTo(TermModel::class, 'school_term', 'id'); // this is for normal laravel format
    }
    /* category_id is the id in the product table, id is the id in category table
    /* so we are much both together here to fetch the actual name */
}