<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermTranslation extends Model
{
    use HasFactory;
    protected $table = 'terms_translations';
    protected $guarded = [];
    public $timestamps = false;
}
