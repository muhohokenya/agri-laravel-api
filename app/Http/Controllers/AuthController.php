<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInterest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'first_name'=>'required|string',
            'last_name'=>'required|string',
            'email'=>'required|string|email|unique:users',
            'password'=>'required|min:8',
            'account_id'=>'required'
        ]);

       $user =  User::query()->create([
            'first_name'=>$request->get('first_name'),
            'last_name'=>$request->get('last_name'),
            'phone_number'=>$request->get('phone_number'),
            'email'=>$request->get('email'),
            'password'=>$request->get('password'),
            'account_id'=>$request->get('account_id'),
        ]);

       if ($request->has('interests')) {
           $interests = $request->get('interests');
           if (count($interests)) {
               $userInterests = [];
               foreach ($interests as $interest) {
                   $userInterests[] = [
                       'user_id' => $user->id,
                       'interest_id' => $interest['id']
                   ];
               }
               UserInterest::query()->insert($userInterests);
           }
       }


        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function getUser(Request $request)
    {
        return response()->json(
            User::query()
                ->where('id', $request->user()->id)
                ->with(['interests','account'])
                ->get()
        );
    }


    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'UnAuthorised!',
                'error_code'=>401
            ], 401);
        }

        $user = User::query()
            ->where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

}
