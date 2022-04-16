<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response($validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $user['token'] = $user->createToken('LaravelSanctumAuth')->plainTextToken;
        return $this->responseFormat(200, 'تم انشاء حسابك بنجاح، قم بتسجيل الدخول', $user);

    }

    public function login(Request $request)
    {


        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->responseFormat(401, __('Invalid login details'), null);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];

        return $this->responseFormat(200, 'تم تسجيل الدخول', $data);
    }
}
