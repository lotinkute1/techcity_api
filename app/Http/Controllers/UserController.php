<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

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
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string',
            'phone_number' => 'required',
        ]);
        $response = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'phone_number' => $request['phone_number'],

        ]);
        return response($response, 201);
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
