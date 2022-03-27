<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'messages' => 'invalid password'
            ], 401);
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        $response = [
            'code' => 201,
            'messages' => 'login successful',
            'data' => [
                'token' => $token
            ]
        ];
        return response()->json($response, 201)->cookie('loginTime', $token, 60 * 24);
    }
    public function loginJWT(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        //Request is validated
        //Crean token
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	'success' => false,
                	'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
    	return $credentials;
            return response()->json([
                	'success' => false,
                	'message' => 'Could not create token.',
                ], 500);
        }

 		//Token created, return with success response and jwt token
        return response()->json([
            'status' => 200,
            'message' => 'login successful',
            'token' => $token
        ])->cookie('loginTime', $token, 60 * 24);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get all users
        $users = User::all();
        return response()->json([
            'code'=>200,
            'message'=>'users list',
            'data'=> $users
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create(Request $request)
    {
        //  $request->validate([
        //     'name' => 'required|string',
        //     'email' => 'required|string|unique:users,email',
        //     'password' => 'required|string',
        //     'phone_number' => 'required',
        // ]);
        $data = $request->only([
            'name','email','password','phone_number'
        ]);
        $validator = Validator::make($data,[
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string',
            'phone_number' => 'required|unique:users,phone_number'
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>403,
                'message' =>$validator->errors()
            ],403);
        }else{

            $response = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
                'phone_number' => $request['phone_number'],

            ]);
            return response()->json([
                'status'=>201,
                'message'=>'register successfully',
                'data' => $response
            ],201);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if($user){
            return response()->json([
                'code'=>200,
                'message'=> 'get user',
                'data'=>$user
            ],200);
        }
        return response()->json([
            'code'=>404,
            'message'=> 'user not found',
        ],404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function logoutJWT(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

		//Request is validated, do logout
        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], 200);
        }
    }
    public function indexJWT(Request $request)
    {

        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);

        return response()->json(['user' => $user]);
    }
    public function logout(Request $request)
    {
        //delete token in database
        Auth::user()->tokens->each(function ($token, $key) {
            $token->delete();
        });
        // delete token in cookie
        $deleteCookie = Cookie::forget('loginTime');


        return response()->json([
            'code' => 200,
            'message' => 'logout successfully'
        ], 201)->withCookie($deleteCookie);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            $user->update($request->all());
            return response()->json([
                'code' => 200,
                'message' => 'updated successfully',
                'data' => $user
            ]);
        }

        return response()->json([
            'code' => 404,
            'message' => 'user not found',
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = User::find($id);
        if ($result) {
            $result->delete();
            return response()->json([
                'code' => 204,
                'message' => 'delete user successfully',
            ]);
        }
        return response()->json([
            'code' => 404,
            'message' => 'user not found',
        ]);
    }
    public function userFilter(Request $request){
        $result = User::where($request['filterType'],'like','%'.$request['filterVal'].'%')->get();
        if(count($result) > 0){
            return response()->json([
                'code'=>201,
                'message'=>'filter user by ' . $request['filterType'],
                'data'=>$result
            ]);
        }else{
            return response()->json([
                'code'=>404,
                'message'=>'not found',
            ]);

        }
    }
}
