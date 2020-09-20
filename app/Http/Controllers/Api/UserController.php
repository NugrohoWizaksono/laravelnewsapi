<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            $user = Auth::user();
            $response = [
                'sukses'=>true,
                'token' => $user->createToken('laranews')->accessToken,
            ];

            return response()->json($response, 200);
        }
        else{
            return response()->json(['error'=> 'unauthorize'], 401);
        }
    }

    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validate->fails())
        {
            return response()->json(['success'=> false, 'message' => $validate->errors()], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'admin' => empty($request->admin) ? 0 : $request->admin,
            'password' => Hash::make($request->password)
        ]);

        $response = [
            'success'=>true,
            'message'=> ' Success Create user'
        ];

        return response()->json($response, 200);
    }

    public function logout(Request $request)
    {
        $logout = $request->user()->token()->revoke();
        if($logout){
            return response()->json([
                'message' => 'logged out'
            ]);
        }
    }

    public function profile()
    {
        $user = Auth::user();
        $response = [
            'success'=>true,
            'data' => $user
        ];
        return response($response, 200);
    }

}
