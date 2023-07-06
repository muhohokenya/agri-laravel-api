<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReplyRequest;
use App\Http\Requests\UpdateReplyRequest;
use App\Models\Reply;
use Illuminate\Support\Facades\DB;

class ReplyController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     @OA\Response(response="200", description="An example endpoint")
     * )
     */
    public function index()
    {
        return response()->json(Reply::query()->with([
            'user','post','upVotes','downVotes'
        ])->get());
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * @OA\Post (
     *      path="/api/reply/create",
     *      operationId="getProjectsList",
     *      tags={"create reply"},
     *      summary="Create reply",
     *      description="Creates a reply",
     *
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function store(StoreReplyRequest $request)
    {
        Reply::query()->create([
            'text'=>$request->get('text'),
            'user_id'=>$request->user()->id,
            'post_id'=>$request->get('post_id')
        ]);


        return response()->json('reply created');
    }

    public function getReplyByID($id){
        return response()->json(Reply::query()->where('id',$id)->with([
            'user','post','upVotes','downVotes'
        ])->get());
    }

    public function getReplyByPost($postId)
    {
        return response()->json(
            Reply::query()->where('post_id', $postId)
                ->with(['user','post','upVotes','downVotes'])
                ->get()
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Reply $reply)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reply $reply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReplyRequest $request, Reply $reply)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reply $reply)
    {
        //
    }
}
