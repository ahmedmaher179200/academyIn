<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher_year extends Model
{
    use HasFactory;
    protected $table = 'teacher_year';

    protected $guarded = [];

    protected $casts = [
        'year_id'       => 'integer',
        'teacher_id'    => 'integer',
    ];
    //relations
    public function Year(){
        return $this->belongsTo(Year::class, 'year_id');
    }

    public function Teacher(){
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
