<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'rating';

    protected $guarded = [];

    protected $casts = [
        'id'             => 'integer',
        'stars'          => 'integer',
        'teacher_id'     => 'integer',
    ];
    //relations
    public function Teacher(){
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
