<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Office extends Authenticatable
{
    use HasFactory;

    protected $table = 'offices';

    protected $guarded = [];

    protected $casts = [
        'id'            => 'integer',
        'verified'      => 'integer',
        'status'        => 'integer',
        'country_id'    => 'integer',
        'balance'       => 'float',
    ];

    public function getImage(){
        return url('public/uploads/admins/default.jpg');
    }
}
