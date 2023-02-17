<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearTranslation extends Model
{
    use HasFactory;
    protected $table = 'years_translations';
    protected $guarded = [];
    public $timestamps = false;
}
