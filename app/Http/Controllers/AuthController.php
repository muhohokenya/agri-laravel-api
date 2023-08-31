<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Interest;
use App\Models\User;
use App\Models\UserInterest;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    private function registerUser($data)
    {
        return User::query()->create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone_number' => $data['phone_number'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
            'account_id' => $data['account_id'],
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required|string|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:8',
            'account_id' => 'required'
        ]);

        $data = [
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'phone_number' => $request->get('phone_number'),
            'email' => $request->get('email'),
            'username' => $request->get('username'),
            'password' => $request->get('password'),
            'account_id' => $request->get('account_id'),
        ];
        $user = $this->registerUser($data);

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

        if ($request->has('other_interests')) {
            $otherInterests = $request->get('other_interests');
            if (count($otherInterests)) {
                $userOtherInterests = [];
                $userOtherInterestsNames = [];
                foreach ($otherInterests as $otherInterest) {

                    //Grab the names of user other interests
                    $userOtherInterestsNames [] = $otherInterest;

                    //Prepare user other interests array
                    $userOtherInterests[] = [
                        'name' => $otherInterest,
                        'status' => 'private',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }

                //Save other interests with private status
                Interest::query()->insert($userOtherInterests);

                //Get ids of user other interests
                $userOtherInterestsIds = Interest::query()
                    ->whereIn('name', $userOtherInterestsNames)
                    ->pluck('id');

                //Associate the user with interests
                $user->interests()->attach($userOtherInterestsIds);
                Log::info($userOtherInterestsIds);
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
                ->with(['interests', 'account'])
                ->get()
        );
    }
    public function getUsers(): AnonymousResourceCollection
    {
        return UserResource::collection(
            User::query()->with(['posts','replies','interests','account'])
                ->get()
        );
    }

    public function getUserById($id): AnonymousResourceCollection
    {
        return UserResource::collection(
            User::query()
                ->where('id', $id)
                ->with(['posts','replies','interests','account'])
                ->get()
        );
    }

    public function deleteUser(Request $request): void
    {
        $id = $request->get('id');
        User::query()
            ->where('id', $id)
            ->delete();
    }

    public function userNameExists(Request $request): JsonResponse
    {
        $username = $request->get('username');
        if (!User::query()->where('username', $username)->exists()) {
            return response()->json([
                "status"=>"available"
            ]);
        }
        return response()->json([
            "status"=>"taken"
        ]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        $token = $request->get('token');
        $password = $request->get('password');

        Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

            }
        );






//        $passwordToken = DB::table('password_reset_tokens')
//            ->where('token', $token);
//
//        $existingToken = $passwordToken->pluck('token');
//
//        $tokenMatch = Hash::check($token, $existingToken);
//
//        if ($tokenMatch) {
//            return response()->json([
//                'status'=>'error',
//                'message'=>'Invalid token',
//            ]);
//        } else {
//            $email = $passwordToken
//                ->pluck('email')->first();
//
//            User::query()
//                ->where('email', $email)
//                ->first()
//                ->update([
//                    'password'=>bcrypt($password)
//                ]);
//
//            return response()->json([
//                'status'=>'success',
//                'message'=>'Password reset is successful',
//            ]);
//        }
    }

    public function requestPasswordReset(Request $request): void
    {
        $request->validate(['email' => 'required|email|exists:users']);
        Password::sendResetLink(
            $request->only('email')
        );
    }

    public function emailExists(Request $request)
    {
        $email = $request->get('email');
        if (!User::query()->where('email', $email)->exists()) {
            return response()->json([
                "status"=>"available"
            ]);
        }
        return response()->json([
            "status"=>"taken"
        ]);
    }


    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'UnAuthorised',
                'error_code' => 401,
                'status'=>'Access Restricted'
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
            'status' => 'success',
            'message' => 'Profile picture updated successfully'
        ]);
    }

    public function updateUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'country' => 'required|string',
            'county' => 'required|string',
        ]);


        User::query()
            ->where('email', $request->user()->email)
            ->update([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'phone_number' => $request->get('phone_number'),
                'country' => $request->get('country'),
                'county' => $request->get('county'),
            ]);
    }

    public function handleGoogleRedirect()
    {
        return Socialite::driver("google")->stateless()->redirect();
    }

    public function handleFaceBookRedirect()
    {
        return Socialite::driver("facebook")->stateless()->redirect();
    }

    public function handleFacebookCallBack()
    {
        $facebookAuthUser = Socialite::driver('facebook')->stateless()->user();
        $fullName = explode(" ", $facebookAuthUser->getName());
        $firstName = Arr::first($fullName);
        $lastName = Arr::last($fullName);
        $phoneNumber = "0711889219";
        $email = $facebookAuthUser->getEmail();
        $accountId = 1;
        $socialAuthProvider = "facebook";
        $socialAuthProviderId = $facebookAuthUser->getId();

        $faceBookRegistrationData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone_number' => $phoneNumber,
            'email' => $email,
            'password' => bcrypt('password'),
            'account_id' => $accountId,
            'social_auth_provider' => $socialAuthProvider,
            'social_auth_provider_id' => $socialAuthProviderId,
        ];
        $this->registerUser($faceBookRegistrationData);
    }

    public function handleProviderCallBack()
    {
        try {
            $socialAuthUser = Socialite::driver('google')->stateless()->user();
            $user = User::query()
                ->where('social_auth_provider', 'google')
                ->where('social_auth_provider_id', $socialAuthUser->getId())
                ->first();

            if (!$user) {
                return redirect()->intended('dashboard');
            } else {
                Auth::login($user);
                return redirect()->intended('dashboard');
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }

}
