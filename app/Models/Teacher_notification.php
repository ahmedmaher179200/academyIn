<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher_notification extends Model
{
    use HasFactory;
    protected $table = 'teacher_notifications';

    protected $guarded = [];

    protected $casts = [
        'id'             => 'integer',
        'student_id'     => 'integer',
        'teacher_id'     => 'integer',
        'available_class_id'=> 'integer',
        'type'           => 'integer',
        'seen'           => 'integer',
    ];
}
