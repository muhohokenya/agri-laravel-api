<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Post::query()->with(['user','replies','votes'=> function($query){
                $upVotes = $query->where('vote', 1);
                $downVotes = $query->where('vote', -1);
                return $upVotes;
            }])->orderBy('created_at', 'desc')->get());
    }

    public function getPostByID($id)
    {
        return response()->json(Post::query()->where('id', $id)->with([
            'user',
        ])->get());
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
        Log::info(json_encode($request->all()));
        Post::query()->create([
            'user_id' => $request->user()->id,
            'title' => $request->get('title'),
            'description' => $request->get('description')
        ]);

        return response()->json("Created");
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
    public function destroy(Post $post)
    {
        //
    }
}
