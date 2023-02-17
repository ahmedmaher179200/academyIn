<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class questions extends Controller
{
    public function index(){
        //select all admin
        $questions = Question::get();
        return view('admins.questions.index')->with('questions', $questions);
    }

    public function delete($question_id){
        //get question 
        $question = Question::find($question_id);

        if($question == null)
            return redirect('admins/questions')->with('error', 'delete faild');

        $question->delete();

        return redirect('admins/questions')->with('success', 'delete level success');
    }
}
