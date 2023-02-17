<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;
    protected $table = 'billings';

    protected $guarded = [];

    protected $casts = [
        'id'                    => 'integer',
        'type'                  => 'integer',
        'billingable_id'                  => 'integer',
        'billingable_type'                  => 'integer',
    ];
}
