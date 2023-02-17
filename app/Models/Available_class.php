<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Available_class extends Model
{
    use HasFactory;
    protected $table = 'available_classes';

    protected $guarded = [];

    protected $casts = [
        'id'                    => 'integer',
        'teacher_id'            => 'integer',
        'subject_id'            => 'integer',
        'class_type_id'         => 'integer',
        'long'                  => 'integer',
        'company_percentage'    => 'integer',
        'promoCode_percentage'  => 'integer',
        'cost'                  => 'float',
        'teacher_mony'          => 'integer',
        'status'                => 'integer',
        'addition'              => 'integer',

    ];
    //relations
    public function Teacher(){
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function Subject(){
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function Class_type(){
        return $this->belongsTo(Class_type::class, 'class_type_id');
    }

    public function Student_classes(){
        return $this->hasMany(Student_class::class, 'available_class_id');
    }

    public function Video_calls(){
        return $this->hasMany(Video_call::class, 'available_class_id');
    }

    public function dateCome(){
        $from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->from);
        $to = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', now());

        if($this->from > date('Y-m-d H:i:s')){
            ($to->diffInMinutes($from) <= 5)? $time_now = 1:  $time_now = 0; //if there are less than 5 minutes for class
        } else {
            $time_now = 1; //date already come
        }        

        return $time_now;
    }

    public function hasBookings(){
        if(DB::table('student_class')->where('available_class_id', $this->id)->count() == 0)
            return false;

        return true;
    }

    public function join(){
        if($this->agora_token != Null && $this->dateCome() == 1 && $this->hasBookings() == true && ($this->to > date('Y-m-d H:i:s'))){
            return true;
        }

        return false;
    }
    //scope
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeNotCome($query)
    {
        return $query->where('to', '>', date('Y-m-d H:i:s'));
    }

    public function scopeCompleteNotPay($query) //if i edit this function edit it in teacher
    {
        return $query->where('status', '3')
                        ->where('teacher_mony', 0);
    }

    public function scopeStartNotPay($query) //if i edit this function edit it in teacher
    {
        return $query->where('status', '2')
                        ->where('teacher_mony', 0);
    }

    public function scopeSchedule($query)
    {
        return $query->where('status', '!=','0')
                        ->where('status', '!=','-1')
                        ->where('to', '>', date('Y-m-d H:i:s'));
    }

    //
    // public function notCome(){
    //     return $this->where('to', '>', date('Y-m-d H:i:s'));
    // }
}
