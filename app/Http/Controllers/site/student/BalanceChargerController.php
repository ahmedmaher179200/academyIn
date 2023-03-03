<?php

namespace App\Http\Controllers\site\student;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Student;
use App\Services\PaymentService;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;


class BalanceChargerController extends Controller
{
    public $StudentService;
    public $PaymentService;
    public function __construct(StudentService $StudentService,
                                PaymentService $PaymentService)
    {
        $this->StudentService = $StudentService;
        $this->PaymentService = $PaymentService;
    }


    public function payment_request(Request $request){
        $validator = Validator::make($request->all(), [
            'amount'     => 'required|integer',
        ]);

        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403);
        $student = auth('student')->user();

        $response = $this->PaymentService->payment_request($request->amount,
                                                            url('api/balanceCharging/' . $student->id),
                                                            url('api/payment/return'));

        return response()->json([
            'successful'        => true,
            'message'           => trans('auth.success'),
            'payment_form_url'  => $response['redirect_url'],
            'tran_ref'          => $response['tran_ref'],
        ], 200);
    }

    public function balance_charging(Request $request, $student_id){
        $student = Student::find($student_id);

        if($request['payment_result']['response_status'] != 'A'){
            return false;
        }

        $this->StudentService->addToBalance($student, $request->tran_total);

        Billing::create([
            'type' => 0,
            'amount' => $request->tran_total,
            'massage'   => 'massage',
            'billingable_id' => $student_id,
            'billingable_type' => 'App\Models\Student',
        ]);

    }

    public function payment_return(Request $request){
        $serverKey = env('paytabs_key'); // Example

        $signature_fields = filter_input_array(INPUT_POST);
        $requestSignature = $signature_fields["signature"];

        unset($signature_fields["signature"]);

        // Ignore empty values fields
        $signature_fields = array_filter($signature_fields);
        
        // Sort form fields 
        ksort($signature_fields);

        // Generate URL-encoded query string of Post fields except signature field.
        $query = http_build_query($signature_fields);

        $signature = hash_hmac('sha256', $query, $serverKey);
        if (hash_equals($signature,$requestSignature) === TRUE) {
            return view('success');
        }else{
            return 'faild';
        }
    }

    public function payment_check(Request $request){
        $validator = Validator::make($request->all(), [
            'tran_ref'       => 'required',
        ]);
        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403, 'E03');

        $response = $this->PaymentService->check($request->get('tran_ref'));

        return json_decode($response, true);
    }
}
