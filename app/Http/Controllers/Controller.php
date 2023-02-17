<?php

namespace App\Http\Controllers;

use App\Traits\response;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Intervention\Image\ImageManagerStatic as Image;
use Aloha\Twilio\Twilio;
use App\Mail\sendCode;
use App\Models\Class_type;
use App\Models\Cost_company_percentage;
use App\Models\Cost_country;
use App\Models\Cost_level;
use App\Models\Cost_year;
use App\Models\Promo_code;
use App\Models\Settings;
use Illuminate\Support\Facades\Mail;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, response;
    public function upload_image($image, $path, $width = 300, $height = 300){
        /*
            $image     image                                => required
            $path      path that i upload image in it       => required
            $width     image with                           => nullable
            $height    image height                         => nullable
        */

        //cange image name to random number
        try {
            $image_name = rand(0,1000000) . time() . '.' . $image->getClientOriginalExtension();
        
            $image_resize = Image::make($image->getRealPath());   
            // $image_resize->resize($width, $height);
            $image_resize->save(public_path($path . '/' . $image_name), 10);
            
            return $image_name;
            
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    public function get_cost($class_type_id, $teacher, $subject){
        //get class_type
        $class_type = Class_type::find($class_type_id);
        if($class_type == null)
            return false;
        
        $setting = Settings::first();

        //get cost_country
        $cost_country = Cost_country::where('country_id', $teacher->id)->first();
        if($cost_country != null){
            $cost_country = $cost_country->cost;
        } else{
            $cost_country = $setting->cost_country;
        }

        //get cost levels
        $cost_level     = Cost_level::where('level_id', $subject->Term->Year->Level->id)->first();
        if($cost_level != null){
            $cost_level = $cost_level->cost;
        } else {
            $cost_level = $setting->cost_level;
        }

        //get cost years
        $cost_year     = Cost_year::where('year_id', $subject->Term->Year->id)->first();
        if($cost_year != null){
            $cost_year = $cost_year->cost;
        } else {
            $cost_year = $setting->cost_year;
        }

        return ($cost_country * $cost_level * $cost_year * $class_type->long_cost) * $class_type->long;
    }

    public function get_company_percentage($teacher){
        $setting = Settings::first();
        //get Cost_company_percentage
        $Cost_company_percentage = Cost_company_percentage::where('country_id', $teacher->country_id)->first();
        if($Cost_company_percentage != null){
            $company_percentage  = $Cost_company_percentage->percentage;
        } else{
            $company_percentage  = $setting->cost_company_percentage;
        }
        
        return $company_percentage;
    }

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

    public function get_price_after_discount($price, $percentage){
        return $price - (($price / 100) * $percentage);
    }

    public function send_message($dialing_code, $phone, $message){
        try {
            $twilio = new Twilio(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'), env('TWILIO_NUMBER'));
            $twilio->message($dialing_code . $phone, $message);
            return 1;
        } catch (Exception $e) {
            return 'phone is wrong';
        }
    }

    public function test(){
        Mail::to('ahmedmaher1792001@gmail.com')->send(new sendCode());
        return 'good';
        $twilio = new Twilio(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'), env('TWILIO_NUMBER'));
        $twilio->message('+966' . '598888518', 'test');
        return 'good';
    }
}
