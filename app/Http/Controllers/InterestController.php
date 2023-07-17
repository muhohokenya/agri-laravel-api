<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreinterestRequest;
use App\Http\Requests\UpdateinterestRequest;
use App\Models\Interest;

class InterestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()
                ->json(Interest::query()
                    ->where('status', 'public')
                    ->get()
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
     * Store a newly created resource in storage.
     */
    public function store(StoreinterestRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(interest $interest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(interest $interest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateinterestRequest $request, interest $interest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(interest $interest)
    {
        //
    }
}
