<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $firstName = $this->first_name;
        $lastName = $this->last_name;
        return [
            "id"=>$this->id ,
            "fullname"=>$firstName. " ". $lastName,
            "phone_number"=>$this->phone_number,
            "country"=>$this->country,
            "county"=>$this->county,
            "email" =>$this->email,
            "username"=>$this->username,
            "created_at"=>$this->created_at,
            "updated_at"=>$this->updated_at,
            "posts"=>$this->posts,
            "replies"=>$this->replies,
            "interests"=>$this->interests,
            "account"=>$this->account,
        ];
    }
}
