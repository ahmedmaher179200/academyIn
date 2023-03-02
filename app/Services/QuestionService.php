<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Traits\response;
use Illuminate\Support\Facades\Hash;

class QuestionService extends Controller
{
    use response;
    public function create($student_id, $subject_id, $question, $image = null){
        $question = Question::create([
            'student_id'    => $student_id,
            'subject_id'    => $subject_id,
            'question'      => $question,
        ]);

        if($image){
            $path = $this->upload_image($image,'uploads/questions', 150, 100);

            $question->image = $path;
            $question->save();
        }

        return $question;
    }

    public function update($question, $request_question, $image= null){
        if($image){
            $path = $this->upload_image($image, 'uploads/questions', 150, 100);

            $question->image = $path;
            $question->save();
        }
        if($question->update(['question'=> $request_question]));

        return true;
    }
}