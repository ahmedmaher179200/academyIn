<?php

namespace App\Http\Controllers\site\guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\main_subjectResource;
use App\Http\Resources\materialResource;
use App\Http\Resources\subjectsResource;
use App\Models\Main_subject;
use App\Models\Subject;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    use response;
    public function index(){
        $main_subjects = Main_subject::active()->get();

        return $this->success(
            trans('auth.success'),
            200,
            'subjects',
            main_subjectResource::collection($main_subjects)
        );
    }

    public function subjects_year(Request $request){
        $validator = Validator::make($request->all(), [
            'year_id'    => 'required|exists:years,id',
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

        $subjects = Subject::active()->whereHas('Term', function($query) use($request){
            $query->where('year_id', $request->get('year_id'));
        })->get();

        return $this->success(
            trans('auth.success'),
            200,
            'subjects',
            subjectsResource::collection($subjects),
        );
    }

    public function materials(Request $request){
        $validator = Validator::make($request->all(), [
            'subject_id'    => 'required|exists:subjects,id',
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

        $materials = Subject::find($request->get('subject_id'))->Materials;

        return $this->success(
            trans('auth.success'),
            200,
            'materials',
            materialResource::collection($materials)
        );
    }
}
