<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use Illuminate\Http\Request;

class answers extends Controller
{
    public function index(Request $request){
        //select all admin
        $answers = Answer::where('question_id', $request->get('question'))->get();
        return view('admins.answers.index')->with('answers', $answers);
    }

    public function delete($answer_id){
        //get answer 
        $answer = Answer::find($answer_id);

        if($answer == null)
            return redirect('admins/answers?question='. $answer->question_id)->with('error', 'delete answer faild');

        $answer->delete();

        return redirect('admins/answers?question='. $answer->question_id)->with('success', 'delete answer success');
    }
}
