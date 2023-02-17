<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    protected $table = 'answers';

    protected $guarded = [];

    protected $casts = [
        'id'                   => 'integer',
        'answerable_id'        => 'integer',
        'question_id'          => 'integer',
        'recommendation'       => 'integer',
        'status'               => 'integer',
    ];

    //relations
    public function Question(){
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function answerable()
    {
        return $this->morphTo();
    }

    public function Student(){
        return $this->belongsTo(Student::class, 'answerable_id');
    }

    public function Teacher(){
        return $this->belongsTo(Teacher::class, 'answerable_id');
    }
    //scope
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    //
    public function getStatus(){
        if($this->status == 0){
            return 'not active';
        } else {
            return 'active';
        }
    }

    public function getType(){
        if($this->answerable_type == "App\Models\Student"){
            return 'student';
        } else {
            return 'teacher';
        }
    }

    public function getUser(){
        if($this->answerable_type == "App\Models\Student"){
            return $this->Student;
        } else {
            return $this->Teacher;
        }
    }

    public function getImage(){
        if($this->image != null){
            return url('public/uploads/answers/' . $this->image);
        } else {
            return null;
        }
    }
}
