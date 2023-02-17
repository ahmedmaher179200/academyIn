<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Material extends Model implements TranslatableContract
{
    use HasFactory, Translatable;
    protected $table = 'materials';
    public $translatedAttributes = ['name'];

    protected $guarded = [];

    protected $casts = [
        'id'            => 'integer',
        'subject_id'    => 'integer',
        'status'        => 'integer',
    ];

    //relations
    public function Subject(){
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function File()
    {
        return $this->morphOne(File::class, 'fileable');
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

    public function getFile(){
        if($this->File != null){
            return url('public/uploads/materials/' . $this->File->src);
        } else {
            return null;
        }
    }
}
