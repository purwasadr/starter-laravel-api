<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{    
    /**
     * index
     *
     * @param  mixed $request
     * @return void
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            'password' => 'required|min:5|max:255'
        ]);
        try {
            $validatedData['password'] = Hash::make($validatedData['password']);
    
            $user = User::create($validatedData);
            $token = $user->createToken(env('APP_KEY'))->plainTextToken;
            Auth::login($user);
    
            return $this->successResponse([
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * login
     *
     * @param  mixed $request
     * @return void
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user= User::where('email', $request->email)->first();
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'success'   => false,
                'message' => ['These credentials do not match our records.']
            ], 404);
        }
    
        $token = $user->createToken(env('APP_KEY'))->plainTextToken;
        
        return $this->successResponse([
            'user'      => $user,
            'token'     => $token
        ]);
    }
    
    /**
     * logout
     *
     * @return void
     */
    public function logout()
    {
        Auth::logout();

        return $this->successResponse('Logout Success');
    }

}