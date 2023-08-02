<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema()
 */
class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this,
//            "image"=>$request->image,
//            "title"=>$request->description,
//            "description"=>$this->description,
//            "user"=>$this->user,
//            "replies"=>$this->replies,
//            "votes"=>$this->up_votes,
        ];
    }
}
