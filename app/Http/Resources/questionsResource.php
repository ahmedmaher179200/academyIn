<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class questionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $is_me = 0;
        if($request->user_id == $this->student_id && $request->guard == 'Student'){
            $is_me = 1;
        }

        return [
            'id'            => $this->id,
            'student_id'    => $this->student_id,
            'question'      => $this->question,
            'status'        => $this->status,
            'answers_count' => count($this->Answers),
            'created_at'    => date("Y-m-d H:i", strtotime($this->created_at)),
            'image'         => $this->getImage(),
            'is_me'         => $is_me,
            'question_owner'=> [
                'id'        => $this->student_id,
                'username'  => $this->Student->username,
                'image'     => $this->Student->getImage(),
            ],
        ];
    }
}
