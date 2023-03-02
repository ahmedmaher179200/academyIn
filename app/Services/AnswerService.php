<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Traits\response;

class AnswerService extends Controller
{
    use response;
    public function create($student_id, $question_id, $answer, $guard, $image = null){
        $answer = Answer::create([
            'answerable_id'    => $student_id,
            'answerable_type'  => 'App\Models\\' . $guard,
            'question_id'      => $question_id,
            'answer'           => $answer,
        ]);

        if($image){
            $path = $this->upload_image($image,'uploads/answers', 450, 300);

            $answer->image = $path;
            $answer->save();
        }

        return $answer;
    }

    public function update($answer, $answer_question, $image= null){
        if($image){
            $path = $this->upload_image($image,'uploads/answers', 150, 100);

            $answer->image = $path;
            $answer->save();
        }

        $answer->update(['answer'=> $answer_question]);

        return true;
    }
}