<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;
    protected $table = 'settings';

    protected $guarded = [];

    protected $casts = [
        'id'                        => 'integer',
        'cost_students_number'      => 'float',
        'cost_level'                => 'float',
        'cost_country'              => 'float',
        'cost_company_percentage'   => 'float',
        'cost_year'                 => 'float',
        'video_company_percentage'  => 'float',
        'live_company_percentage'   => 'float',
    ];
}
