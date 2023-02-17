<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject_teachersss extends Model
{
    use HasFactory;
    protected $table = 'subject_teacher';

    protected $guarded = [];

    protected $casts = [
        'subject_id'    => 'integer',
        'teacher_id'    => 'integer',
    ];
    //relations
    public function Subject(){
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function Teacher(){
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
