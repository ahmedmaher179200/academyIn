<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Level extends Model implements TranslatableContract
{
    use HasFactory, Translatable;
    protected $table = 'levels';

    public $translatedAttributes = ['name'];
    protected $guarded = [];

    protected $casts = [
        'id'            => 'integer',
        'curriculum_id' => 'integer',
        'status'        => 'string',
    ];

    //relations
    public function Curriculum(){
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }
    public function Years(){
        return $this->hasMany(Year::class, 'level_id');
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
