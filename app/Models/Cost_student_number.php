<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cost_student_number extends Model
{
    use HasFactory;
    protected $table = 'cost_students_numbers';

    protected $guarded = [];

    protected $casts = [
        'id'                    => 'integer',
        'min_students_number'   => 'integer',
        'max_students_number'   => 'integer',
        'cost'                  => 'float',
    ];
}
