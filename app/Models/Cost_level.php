<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cost_level extends Model
{
    use HasFactory;
    protected $table = 'cost_levels';

    protected $guarded = [];

    protected $casts = [
        'id'         => 'integer',
        'level_id'   => 'integer',
        'cost'       => 'float',
    ];

    //relations
    public function Level(){
        return $this->belongsTo(Level::class, 'level_id');
    }
}
