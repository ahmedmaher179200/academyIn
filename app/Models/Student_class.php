<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student_class extends Model
{
    use HasFactory;
    protected $table = 'student_class';

    protected $guarded = [];

    protected $casts = [
        'student_id'            => 'integer',
        'available_class_id'    => 'integer',
        'promocode_id'          => 'integer',
        'promocode_descount'    => 'integer',
    ];

    //relations
    public function Students(){
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function Available_class(){
        return $this->belongsTo(Available_class::class, 'available_class_id');
    }
}
