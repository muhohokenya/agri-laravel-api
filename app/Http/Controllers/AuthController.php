<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInterest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    private $socialAuthProvider;
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

    public function updateProfilePicture(Request $request)
    {
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $destinationPath = 'uploads/profiles';
            $file->move($destinationPath, $file->getClientOriginalName());
        }

        return response()->json([
            'status'=>'success',
            'message'=>'Profile picture updated successfully'
        ]);
    }

    public function updateUser(Request $request)
    {
        $request->validate([
            'first_name'=>'required|string',
            'last_name'=>'required|string',
            'country'=>'required|string',
            'county'=>'required|string',
        ]);



        User::query()
            ->where('email', $request->user()->email)
            ->update([
            'first_name'=>$request->get('first_name'),
            'last_name'=>$request->get('last_name'),
            'phone_number'=>$request->get('phone_number'),
            'country'=>$request->get('country'),
            'county'=>$request->get('county'),
        ]);
    }

    public function handleGoogleRedirect($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleFaceBookRedirect()
    {
        return Socialite::driver("facebook")->stateless()->redirect();
    }

    public function handleFacebookCallBack(){
        $facebookAuthUser = Socialite::driver('facebook')->stateless()->user();
        dd($facebookAuthUser);
    }
    public function handleProviderCallBack()
    {
        try {
            $socialAuthUser = Socialite::driver('google')->stateless()->user();

            dd($socialAuthUser);
            $user = User::query()
                ->where('social_auth_provider', 'google')
                ->where('social_auth_provider_id', $socialAuthUser->getId())
                ->exists();

            if (!$user) {
               $newUser =  User::query()->create([
                    'first_name'=>$socialAuthUser->getName(),
                    'last_name'=>$socialAuthUser->getName(),
                    'phone_number'=>"0711555666",
                    'email'=>$socialAuthUser->getEmail(),
                    'password'=>bcrypt('password'),
                    'account_id'=>1,
                    'social_auth_provider'=>"google",
                    'social_auth_provider_id'=>$socialAuthUser->getId(),
                ]);

               Auth::login($newUser);

               return redirect()->intended('dashboard');
            } else {
                Auth::login($user);

                return redirect()->intended('dashboard');
            }
        }catch (\Exception $ex) {
         return $ex;
        }
    }

}
