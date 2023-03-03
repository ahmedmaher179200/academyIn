<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Models\Promo_code;
use App\Traits\response;
use Illuminate\Support\Facades\Hash;

class PromoCodeService extends Controller
{
    use response;
    public function promo_code_percentage($promo_code){
        $percentage = 0;

        $promo_code = Promo_code::where('code', $promo_code)
                                ->active()
                                ->first();
            
        if($promo_code != null){
            $percentage = $promo_code->percentage;
        }
        return $percentage;
    }

    public static function get_price_after_discount($price, $percentage){
        return $price - (($price / 100) * $percentage);
    }
}