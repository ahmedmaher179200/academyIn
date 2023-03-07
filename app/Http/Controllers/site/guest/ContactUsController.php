<?php

namespace App\Http\Controllers\site\guest;

use App\Http\Controllers\Controller;
use App\Models\Contact_us;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactUsController extends Controller
{
    use response;
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'email'       => 'required|string',
            'title'       => 'required|string',
            'content'     => 'required|string',
        ]);

        Contact_us::create([
            'email' => $request->email,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

        return response()->json([
            'successful'        => true,
            'message'           => trans('auth.success'),
        ], 200); 
    }
}
