<?php

namespace App\Http\Controllers\site\student;

use App\Http\Controllers\Controller;
use App\Http\Resources\offersResource;
use App\Http\Resources\studentResource;
use App\Models\Offer;
use App\Services\StudentService;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OfferController extends Controller
{
    public $StudentService;
    public function __construct(StudentService $StudentService)
    {
        $this->StudentService = $StudentService;
    }

    public function index(){
        $offers = Offer::get();

        return $this->success(
                        trans('auth.success'),
                        200,
                        'offers',
                        offersResource::collection($offers)
                    );
    }

    public function take_offer(Request $request){
        $validator = Validator::make($request->all(), [
            'offer_id'     => 'required|integer|exists:offers,id',
        ]);

        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403);

        try{
            DB::beginTransaction();
            $student = auth('student')->user();

            $offer = Offer::find($request->get('offer_id'));

            if($this->StudentService->checkBalance($student, $offer->price) == false){
                return response()->json([
                    'successful'    => false,
                    'not_enough'    => true,
                    'message'       => trans('site.your balance not enough'),
                ], 400);
            }

            $this->StudentService->addToBalance($student, -1 * $offer->price); //subtract form balance

            $student->free      += $offer->classes_count;
            $student->save();

            DB::commit();
            return $this->success(trans('auth.success'), 200, 'student', new studentResource($student));
        } catch(\Exception $ex){
            return $this->faild(trans('auth.faild'), 400);
        }
    }
}
