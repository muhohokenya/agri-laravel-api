<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PostResource::collection(
            Post::query()->with(
                ['user','replies',
                'upVotes'=> function ($query)
                {
                   return $query->where('vote', 1);
                },
                'downVotes'=> function ($query)
                {
                   return $query->where('vote', -1);
                }
            ])->orderBy('created_at', 'desc')->get()
        );
    }

    public function getPostByID($id)
    {
        return PostResource::collection(
            Post::query()
                ->where('id', $id)
                ->with(
                ['user','replies',
                    'upVotes'=> function ($query)
                    {
                         $query->where('vote', 1);
                    },
                    'downVotes'=> function ($query)
                    {
                         $query->where('vote', -1);
                    }
                ])->orderBy('created_at', 'desc')->get()
        );
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/post/create",
     *     tags={"create post"},
     *     summary="Adds a new user - with oneOf examples",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="id",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     oneOf={
     *                     	   @OA\Schema(type="string"),
     *                     	   @OA\Schema(type="integer"),
     *                     }
     *                 ),
     *                 example={"name": "What is the optimum environment for apple farming"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/PostResource"),
     *                 @OA\Schema(type="boolean")
     *             },
     *             @OA\Examples(example="result", value={"success": true}, summary="An result object."),
     *             @OA\Examples(example="bool", value=false, summary="A boolean value."),
     *         )
     *     )
     * )
     */
    public function store(StorePostRequest $request)
    {
        $fileName = "";
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $destinationPath = 'uploads/posts';
            $fileName = $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);
        }




        $data = Post::query()->create([
            'user_id' => $request->user()->id,
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'image' => $fileName
        ]);
        $postId = $data->id;
        return $this->getPostByID($postId);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($postId)
    {
        Post::query()->where('id' , $postId)->delete();
    }
}
