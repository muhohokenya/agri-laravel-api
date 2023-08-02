<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostVotesRequest;
use App\Http\Requests\StoreVoteRequest;
use App\Http\Requests\UpdatePostVotesRequest;
use App\Models\PostVotes;
use Illuminate\Http\Response;

class PostVotesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function votePost(StoreVoteRequest $request)
    {

        PostVotes::query()->updateOrCreate([
            'user_id'=>$request->user()->id,
            'post_id'=>$request->get('post_id'),
        ], ['vote'=> $request->get('vote')]);

        return response()->json([
            'response'=>'created',
            'code'=>Response::HTTP_CREATED
        ])->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(PostVotes $postVotes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PostVotes $postVotes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostVotesRequest $request, PostVotes $postVotes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PostVotes $postVotes)
    {
        //
    }
}
