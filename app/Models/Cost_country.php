<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cost_country extends Model
{
    use HasFactory;
    protected $table = 'cost_countries';

    protected $guarded = [];

    protected $casts = [
        'id'            => 'integer',
        'country_id'    => 'integer',
        'cost'          => 'float',
    ];

    //relations
    public function Country(){
        return $this->belongsTo(Country::class, 'country_id');
    }
}
