<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class answersResource extends JsonResource
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
        if($request->user_id == $this->answerable_id && strpos($this->answerable_type, $request->guard)){
            $is_me = 1;
        }

        return [
            'id'            => $this->id,
            'answer'        => $this->answer,
            'question_id'   => $this->question_id,
            'status'        => $this->status,
            'recommendation'=> $this->recommendation,
            'image'         => $this->getImage(),
            'is_me'         => $is_me,
            'created_at'    => date("Y-m-d H:i", strtotime($this->created_at)),
            'answer_owner'  => [
                'id'        => $this->answerable_id,
                'username'  => $this->getUser()->username,
                'type'      => $this->getType(),
                'image'     => $this->getUser()->getImage(),
            ],
        ];
    }
}
