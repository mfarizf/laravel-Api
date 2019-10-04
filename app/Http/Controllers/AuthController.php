<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use Carbon\Carbon;
use App\User;
use App\Transformers\UserTransform;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @return [string] message
     */

    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6',
        ]);

        $user = new User([
            'name'      => $request->name,
            'email'     => $request->email,
            'api_token' => bcrypt($request->email),
            'password'  => bcrypt($request->password)  
        ]);
        
        $user->save();
        return response()->json([
        'name'      => $request->name,
        'email'     => $request->email,
        'api_token' => bcrypt($request->email)],201);
    }

    public function login(Request $request, User $user)
    {

        if(!Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            return response()->json(['error' => 'Your credential is wrong'], 401);
        }

        $user = $user->find(Auth::user()->id);

        return fractal()
            ->item($user)
            ->transformWith(new UserTransform)
            ->addMeta([
                'token' => $user->api_token,    
            ])
            ->toArray();
    }
}
 