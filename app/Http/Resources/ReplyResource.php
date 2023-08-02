<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReplyResource extends JsonResource
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
            'id'=>$this->id,
            'text'=>$this->text,
            'user_id'=>$this->user_id,
            'post_id'=>$this->post_id,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'user'=>$this->user,
            'post'=>$this->post,
            'votes'=>$totalVotes,
        ];
    }
}
