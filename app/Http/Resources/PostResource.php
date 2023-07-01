<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

<<<<<<< HEAD
=======
/**
 * @OA\Schema()
 */
>>>>>>> f3740d54e46043c328c15430fa943db4a007caba
class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
