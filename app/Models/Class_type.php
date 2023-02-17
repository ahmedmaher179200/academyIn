<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Class_type extends Model
{
    use HasFactory;
    protected $table = 'class_types';

    protected $guarded = [];

    protected $casts = [
        'id'                    => 'integer',
        'long'                  => 'integer',
        'long_cost'             => 'float',
        'status'                => 'integer',
    ];

    //relations
    public function Country(){
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function Available_classes(){
        return $this->hasMany(Available_class::class, 'class_type_id');
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

    public function getCost(){

    }
}
