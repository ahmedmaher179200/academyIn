<?php

namespace App\Http\Controllers\site\student;

use App\Http\Controllers\Controller;
use App\Http\Resources\liveResource;
use App\Models\Live;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class lives extends Controller
{
    public function lives(Request $request){
        // validate
        $validator = Validator::make($request->all(), [
            'subject_id'          => 'nullable|integer|exists:subjects,id',
            'year_id'             => 'nullable|integer|exists:years,id',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403);
        }

        if($request->get('subject_id') != null){
            $lives = Live::notCome()
                            ->where('subject_id', $request->get('subject_id'))
                            ->get();
        }
        
        if($request->get('year_id') != null){
            $lives = Live::notCome()
                            ->whereHas('Subject', function($query) use($request){
                                $query->whereHas('Term', function($query1) use($request){
                                    $query1->where('year_id', $request->get('year_id'));
                                });
                            })
                            ->get();
        }

        if($request->get('year_id') == null && $request->get('subject_id') == null){
            return $this->faild(
                trans('site.you shold enter year_id or subject_id'),
                400,
            );
        }


        return $this->success(
            trans('auth.success'),
            200,
            'lives',
            liveResource::collection($lives)
        );
    }
}
