<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Subject extends Model
{
    use HasFactory;
    protected $table = 'subjects';
    protected $guarded = [];

    protected $casts = [
        'id'                    => 'integer',
        'term_id'               => 'integer',
        'main_subject_id'       => 'integer',
        'status'                => 'integer',
    ];
    
    //relations
    public function Term(){
        return $this->belongsTo(Term::class, 'term_id');
    }

    public function Main_subject(){
        return $this->belongsTo(Main_subject::class, 'main_subject_id');
    }

    public function Subject_teachers(){
        return $this->hasMany(Subject_teacher::class, 'subject_id');
    }

    public function Available_classes(){
        return $this->hasMany(Available_class::class, 'subject_id');
    }

    public function Materials(){
        return $this->hasMany(Material::class, 'subject_id');
    }

    public function Image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function Student_classes(){
        return $this->hasMany(Student_class::class, 'student_id');
    }

    //scope
    public function scopeActive($query)
    {
        return $query->where('status', 1)
                    ->whereHas('Main_subject', function($query){
                        $query->where('status', 1);
                    });
    }

    //
    public function getStatus(){
        if($this->status == 0){
            return 'not active';
        } else {
            return 'active';
        }
    }

    // public function getImage(){
    //     if($this->Image != null){
    //         return url('public/uploads/subjects/' . $this->Image->src);
    //     } else {
    //         return url('public/uploads/subjects/default.jpg');
    //     }
    // }
}
