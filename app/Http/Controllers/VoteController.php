<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoteRequest;
use App\Http\Requests\UpdateVoteRequest;
use App\Models\PostVotes;
use App\Models\ReplyVotes;
use Illuminate\Http\Response;

class VoteController extends Controller
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

    /**
     * Store a newly created resource in storage.
     */
    public function voteReply(StoreVoteRequest $request)
    {

        ReplyVotes::query()->updateOrCreate([
           'user_id'=>$request->user()->id,
           'reply_id'=>$request->get('reply_id'),
       ], ['vote'=> $request->get('vote')]);

       return response()->json([
           'response'=>'created',
           'code'=>Response::HTTP_CREATED
       ])->setStatusCode(Response::HTTP_CREATED);
    }



    /**
     * Display the specified resource.
     */
    public function show(ReplyVotes $vote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReplyVotes $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVoteRequest $request, ReplyVotes $vote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReplyVotes $vote)
    {
        //
    }
}
