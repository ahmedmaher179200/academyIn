<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cost_year extends Model
{
    use HasFactory;
    protected $table = 'cost_years';

    protected $guarded = [];

    protected $casts = [
        'id'         => 'integer',
        'year_id'   => 'integer',
        'cost'       => 'float',
    ];

    //relations
    public function Year(){
        return $this->belongsTo(Year::class, 'year_id');
    }
}
