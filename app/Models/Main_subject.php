<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Main_subject extends Model implements TranslatableContract
{
    use HasFactory, Translatable;
    protected $table = 'main_subjects';
    public $translatedAttributes = ['name'];
    protected $guarded = [];

    protected $casts = [
        'id'            => 'integer',
        'status'        => 'integer',
    ];

    //scope
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function Image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function getImage(){
        if($this->Image != null){
            return url('public/uploads/main_subjects/' . $this->Image->src);
        } else {
            return url('public/uploads/main_subjects/default.png');
        }
    }

    public function getStatus(){
        if($this->status == 0){
            return 'not active';
        } else {
            return 'active';
        }
    }
}
