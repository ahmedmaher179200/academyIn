<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Main_subjectTranslation extends Model
{
    use HasFactory;
    protected $table = 'main_subjects_translations';
    protected $guarded = [];
    public $timestamps = false;
}
