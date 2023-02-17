<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;

class Admin extends Authenticatable
{
    use LaratrustUserTrait;
    use HasFactory;
    protected $table = 'admins';

    protected $guarded = [];
    //relations
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    //
    public function getImage(){
        if($this->Image != null){
            return url('public/uploads/admins/' . $this->Image->src);
        } else {
            return url('public/uploads/admins/default.jpg');
        }
    }

    public function getRole(){
        if(count($this->roles) > 0){
            return $this->roles[0]->name;
        } else {
            return null;
        }
    }

    public function getRoleId(){
        if(count($this->roles) > 0){
            return $this->roles[0]->id;
        } else {
            return null;
        }
    }
}
