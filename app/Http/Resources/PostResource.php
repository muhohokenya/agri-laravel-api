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
        $upVotes = self::collection($this->upVotes)->count();
        $downVotes = self::collection($this->downVotes)->count();
        $totalVotes = $upVotes - $downVotes;
        return [
            "id"=>$this->id,
            "image"=>public_path('uploads/posts/'.$this->image),
            "title"=>$this->title,
            "description"=>$this->description,
            "user"=>$this->user,
            "replies"=>$this->replies,
//            "votes"=>($totalVotes < 0) ? 0 : $totalVotes,
            "votes"=>$totalVotes,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
        ];
    }
}
