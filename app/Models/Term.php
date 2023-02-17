<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Term extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    protected $table = 'terms';
    public $translatedAttributes = ['name'];

    protected $guarded = [];

    protected $casts = [
        'id'            => 'integer',
        'year_id'       => 'integer',
        'status'        => 'integer',
    ];

    //relations
    public function Year(){
        return $this->belongsTo(Year::class, 'year_id');
    }

    public function Subjects(){
        return $this->hasMany(Subject::class, 'term_id');
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
