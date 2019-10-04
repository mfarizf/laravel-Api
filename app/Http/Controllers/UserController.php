<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Transformers\UserTransform;

class UserController extends Controller
{
    public function users(User $user){
        $users = $user->all();
        return fractal()
            ->collection($users)
            ->transformWith(new UserTransform)
            ->toArray();
    }
}
