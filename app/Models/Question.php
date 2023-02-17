<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $table = 'questions';

    protected $guarded = [];

    protected $casts = [
        'id'            => 'integer',
        'student_id'    => 'integer',
        'subject_id'    => 'integer',
        'status'        => 'integer',
    ];

    //relations
    public function Student(){
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function Subject(){
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function Answers(){
        return $this->hasMany(Answer::class, 'question_id');
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

    public function getImage(){
        if($this->image != null){
            return url('public/uploads/questions/' . $this->image);
        } else {
            return null;
        }
    }
}
