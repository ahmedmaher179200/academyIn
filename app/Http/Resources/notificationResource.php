<?php

namespace App\Http\Resources;

use App\Http\Controllers\admin\students;
use App\Models\Answer;
use App\Models\Available_class;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Resources\Json\JsonResource;

class notificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        ($request->header('lang') == 'ar')? $lang = 'ar': $lang = 'en';

        $teacher       = $this->get_teacher();
        $classStudents = $this->getclassStudents($request);
        $agoraResponse = $this->agoraResponse($classStudents['students'], $classStudents['student'], $teacher, $lang);
        $target_student = $this->get_target_student();

        return [
            'id'                    => $this->id,
            'title'                 => $this->title,
            'content'               => $this->content,
            'type'                  => $this->type,
            'seen'                  => $this->seen,
            'created_at'            => date("Y-m-d H:i:s", strtotime($this->created_at)),
            'answer'                => $this->get_answer($this->answer_id),
            'agora'                 => $agoraResponse,
            'teacher'               => $teacher,
            'students'              => $classStudents['students']->map(function ($data) {
                return [
                    'id'        => $data->id,
                    'username'  => $data->username,
                    'image'     => $data->getImage(),
                ];
            }),
            'student'               => $classStudents['student'],
            'target_student'        => $target_student,
        ];
    }

    public function getclassStudents($request){
        if($request->student == null){
            $students = Student::WhereHas('Student_classes', function($query){
                $query->where('available_class_id', $this->available_class_id);//
            })
            ->get();

            $student = null;
        } else {
            $students = Student::WhereHas('Student_classes', function($query){
                $query->where('available_class_id', $this->available_class_id);//
            })
            ->where('id', '!=', $request->student->id)
            ->get();

            $student = [
                'id'        => $request->student->id,
                'username'  => $request->student->username,
                'image'     => $request->student->getImage(),
            ];
        }

        return [
            'students'  => $students,
            'student'   => $student,
        ];
    }

    public function agoraResponse($students, $student, $teacher, $lang){
        if($this->agora_token == null){
            $agora = null;
        } else {
            $available_class = $this->get_available_class($lang);

            $agora = [
                'token'       => $this->agora_token,
                'agora_rtm_token'   => $this->agora_rtm_token,
                'rtm_user_id'       => 'teacher_' . $teacher['id'],
                'channel_name'      => $this->agora_channel_name,
                'available_class'   => $available_class,
                'teacher'           => $teacher,
                'students'          => $students->map(function ($data) {
                    return [
                        'id'        => $data->id,
                        'username'  => $data->username,
                        'image'     => $data->getImage(),
                    ];
                }),
                'student'           => $student,
            ];
        }

        return $agora;
    }

    public function get_available_class($lang){
        $available_class = Available_class::find($this->available_class_id);

        if($available_class != null){
            $available_class = [
                'id'    => $available_class->id,
                'to'    => $available_class->to,
                'from'  => $available_class->from,
                'subject'   => [
                                    'id'    => $available_class->Subject->id,
                                    'name'  => $available_class->Subject->Main_subject->translate($lang)->name,
                                ],
            ];
        } else {
            $available_class = null;
        }
        return $available_class;
    }

    public function get_teacher(){
        $teacher = Teacher::find($this->teacher_id);
        if($teacher != null){
            $teacher = [
                'id'        => $teacher->id,
                'username'  => $teacher->username,
                'image'     => $teacher->getImage(),
            ];
        } else {
            $teacher = null;
        }

        return $teacher;
    }

    public function get_target_student(){
        $target_student = Student::find($this->student_id);
        if($target_student == null)
            return null;

        return [
            'id'        => $target_student->id,
            'username'  => $target_student->username,
            'image'     => $target_student->getImage(),
        ];
    }

    public function get_answer($answer_id){
        if(!$answer_id)
            return null;

        $answer = Answer::find($answer_id);
        
        return new answersResource($answer);
    }   
}
