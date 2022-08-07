<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaysSchoolOpen extends Model
{
    use HasFactory;
    protected $table = "days_school_opens";
    protected $fillable = [
        'days_open',
        'open_term',
        'open_year',
        'open_status',
        'open_date',
        'open_addedby',
    ];

    /* this will create a database relationship between category
    /* and product table */

    protected $with = ['schoolyear', 'schoolterm'];  // this will pass it to javascript, jquery or any other programming platform outside laravel
    public function schoolyear()
    {
        return $this->belongsTo(AcademicSession::class, 'open_year', 'id'); // this is for normal laravel format
    }
    /* category_id is the id in the product table, id is the id in category table
    /* so we are much both together here to fetch the actual name */

    public function schoolterm()
    {
        return $this->belongsTo(TermModel::class, 'open_term', 'id'); // this is for normal laravel format
    }
    /* category_id is the id in the product table, id is the id in category table
    /* so we are much both together here to fetch the actual name */
}