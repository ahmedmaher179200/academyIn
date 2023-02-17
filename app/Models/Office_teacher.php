<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office_teacher extends Model
{
    use HasFactory;

    protected $table = 'office_teacher';

    protected $guarded = [];

    protected $casts = [
        'id'            => 'integer',
        'office_id'     => 'integer',
        'teacher_id'    => 'integer',
    ];
}
