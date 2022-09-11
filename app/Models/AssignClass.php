<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignClass extends Model
{
    use HasFactory;
    protected $table = "assign_classes";
    protected $fillable = [
        'cls_teacher_id',
        'cls__class_id',
        'cls__teacher_name',
        'cls__class_name',
        'cls__status',
        'cls__tid',
        'cls__addby',
        'cls__date',
    ];
}