<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $table = 'videos';

    protected $guarded = [];
    protected $casts = [
        'id'            => 'integer',
        'teacher_id'    => 'integer',
        'subject_id'    => 'integer',
        'cost'          => 'integer',
        'status'        => 'integer',
    ];

    //relations
    public function Teacher(){
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function Subject(){
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    //scope
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
