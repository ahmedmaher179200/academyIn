<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Curriculum extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    protected $table = 'curriculums';
    public $translatedAttributes = ['name'];

    protected $guarded = [];

    protected $casts = [
        'id'            => 'integer',
        'country_id'    => 'integer',
        'status'        => 'integer',
    ];

    //relations
    public function Country(){
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function Curriculums(){
        return $this->hasMany(Curriculum::class, 'curriculum_id');
    }
    
    public function Levels(){
        return $this->hasMany(Level::class, 'curriculum_id');
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

}
