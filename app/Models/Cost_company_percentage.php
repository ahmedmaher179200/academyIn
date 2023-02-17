<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cost_company_percentage extends Model
{
    use HasFactory;
    protected $table = 'cost_company_percentages';

    protected $guarded = [];

    protected $casts = [
        'id'            => 'integer',
        'country_id'    => 'float',
        'percentage'    => 'float',
    ];

    //relations
    public function Country(){
        return $this->belongsTo(Country::class, 'country_id');
    }
}
