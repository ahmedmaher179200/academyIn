<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video_call extends Model
{
    use HasFactory;
    protected $table = 'video_calls';

    protected $guarded = [];

    protected $casts = [
        'id'                    => 'integer',
        'available_classes_id'  => 'integer',
        'status'                => 'integer',
    ];

    //relations
    public function Available_class(){
        return $this->belongsTo(Available_class::class, 'available_classes_id');
    }
}
