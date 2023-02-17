<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo_code extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'promo_codes';

    protected $guarded = [];

    protected $casts = [
        'id'            => 'integer',
        'percentage'    => 'integer',
        'status'        => 'integer',
    ];
    //scope
    public function scopeActive($query)
    {
        return $query->where('status', 1)
                    ->where('expiration', '>', date('Y-m-d H:i:s'));
    }
    
    //relations
    public function getStatus(){
        if($this->status == 0){
            return 'not active';
        } else {
            return 'active';
        }
    }
}
