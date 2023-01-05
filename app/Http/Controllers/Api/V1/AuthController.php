<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    public function login(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');

        if(empty($email)){
            return response()->json([
                'success'=>false,
                'message' => 'Email Id is Empty',
            ], 200);

        }
        if(empty($password)){
            return response()->json([
                'success'=>false,
                'message' => 'Password is Empty',
            ], 200);

        }

        $user = User::where('email', $email)
        ->where('password',$password)
        ->get();

        if (count($user)==1) {
            return response()->json([
                'success'=>true,
                'message' => 'Logged In successfully',
                'data' => [$user],
            ], 200);
        }

        return response()->json([
            'success'=>false,
            'message' => 'User Not Found Or Invalid Credentials',
        ], 401);
    }
    
}
