<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Live extends Model
{
    use HasFactory;
    protected $table = 'lives';

    protected $guarded = [];

    protected $casts = [
        'id'                => 'integer',
        'teacher_id'        => 'integer',
        'subject_id'        => 'integer',
        'cost'              => 'float',
        'company_percentage'=> 'integer',
        'status'            => 'integer',
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
    //scope
    public function scopeNotCome($query)
    {
        return $query->where('status', 1)
                        ->where('from', '>', date('Y-m-d H:i:s'));
    }
}
