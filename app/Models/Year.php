<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Year extends Model implements TranslatableContract
{
    use HasFactory, Translatable;
    protected $table = 'years';

    public $translatedAttributes = ['name'];

    protected $guarded = [];

    protected $casts = [
        'id'            => 'integer',
        'level_id'      => 'integer',
        'status'        => 'integer',
    ];

    //relations
    public function Students(){
        return $this->hasMany(Student::class, 'year_id');
    }

    public function Level(){
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function Terms(){
        return $this->hasMany(Term::class, 'year_id');
    }

    public function Teacher_years(){
        return $this->hasMany(Teacher_year::class, 'year_id');
    }

    public function Subjects()
    {
        return $this->hasManyThrough(Subject::class, Term::class);
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
