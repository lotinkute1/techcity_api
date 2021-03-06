<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
                'user' => $user,
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
            'code' => 200,
            'message' => 'users list',
            'data' => $users
        ], 200);
    }
    public function loginGG(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string',
            'ava' => 'string',
            'phone_number' => 'numeric',
            'address' => 'string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                'message' => $validator->errors()
            ], 403);
        }
        // login if exist user
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $token = $user->createToken('myapptoken')->plainTextToken;
            $response = [
                'code' => 201,
                'messages' => 'login google successful',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ];
            return response()->json($response, 201)->cookie('loginTime', $token, 60 * 24);
        }

        //register
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|unique:users,email',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                'message' => $validator->errors()
            ], 403);
        }
        $userCreated = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt('google'),
            'phone_number' => $request['phone_number'],
            'address' => $request['address'],
            'ava'=>$request['ava']

        ]);
        $token = $userCreated->createToken('myapptoken')->plainTextToken;
        $response = [
            'code' => 201,
            'messages' => 'register and login google successful',
            'data' => [
                'user' => $userCreated,
                'token' => $token
            ],
        ];
        return response()->json($response, 201)->cookie('loginTime', $token, 60 * 24);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string',
            'phone_number' => 'numeric|unique:users,phone_number',
            'address' => 'string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                'message' => $validator->errors()
            ], 403);
        } else {

            $response = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
                'phone_number' => $request['phone_number'],
                'address' => $request['address'],
                'ava' => $request['ava']

            ]);
            return response()->json([
                'status' => 201,
                'message' => 'register successfully',
                'data' => $response
            ], 201);
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
        if ($user) {
            return response()->json([
                'code' => 200,
                'message' => 'get user',
                'data' => $user
            ], 200);
        }
        return response()->json([
            'code' => 404,
            'message' => 'user not found',
        ], 404);
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
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'email' => 'string|unique:users,email',
            'password' => 'string',
            'phone_number' => 'numeric|unique:users,phone_number',
            'address' => 'string',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 403,
                'message' => $validator->errors()
            ], 403);
        }
        if ($user) {
            $request['password'] = bcrypt($request['password']);
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
    public function userFilter(Request $request)
    {
        $result = User::where($request['filterType'], 'like', '%' . $request['filterVal'] . '%')->get();
        if (count($result) > 0) {
            return response()->json([
                'code' => 201,
                'message' => 'filter user by ' . $request['filterType'],
                'data' => $result
            ]);
        } else {
            return response()->json([
                'code' => 404,
                'message' => 'not found',
            ]);
        }
    }
    public function getPopularSuppliers($pubularType) {
        $suppliers = User::selectRaw('
        users.*,
        sum(order_details.price * order_details.number) as total_sold_price,
        sum(order_details.number) as total_products_sold
        ')
        ->leftJoin('products','users.id','=','products.user_id')
        ->leftJoin('order_details','order_details.product_id','=','products.id')
        ->where('users.role','=',1)
        ->groupBy('users.id')
        ->orderBy($pubularType,'desc')//total_sold_price or total_products_sold
        ->get();
        // dd($query->toSql(), $query->getBindings());
        if(count($suppliers)<1){
            return response()->json([
                'code' => 404,
                'message' => 'get popular suppliers fail',
            ],404);
        }
        return response()->json([
            'code' => 200,
            'message' => 'get popular suppliers successfully',
            'data' =>$suppliers
        ],200);
    }
    public function getSoldData() {
        $currentUser = Auth::user();
        $ratingCount = Rating::selectRaw('count(*) as rating_count')
        ->leftJoin('products', 'products.id','product_id')
        ->leftJoin('users','products.user_id','users.id');
        $productCount = Product::selectRaw('count(*) as product_count');
        $orderTotalPrice =DB::table('order_details')->selectRaw('sum(order_details.price * order_details.number) as order_total,sum(order_details.number) as order_product_count')
        ->leftJoin('products','products.id','order_details.product_id');
        $soldByMonth = OrderDetail::selectRaw('
            sum(order_details.number) as sold,  month( order_details.created_at) as month
        ')->leftJoin('products','products.id','order_details.product_id')->groupBy(DB::raw('month( order_details.created_at)'));
        if($currentUser->role ==1){
            $ratingCount->where('users.id', '=', $currentUser->id);
            $productCount->where('products.user_id', '=',$currentUser->id);
            $orderTotalPrice->where('products.user_id', '=',$currentUser->id);
            $soldByMonth->where('products.user_id', '=',$currentUser->id);
        };

        return response()->json([
            'code' => 200,
            'message'=> 'get sold data',
            'data'=>[
                'rating_count' => $ratingCount->get()[0]->rating_count,
                'product_count'=>  $productCount->get()[0]->product_count,
                'order_total'=> $orderTotalPrice->get()[0]->order_total,
                'getOrderProductCount'=>$orderTotalPrice->get()[0]->order_product_count,
                'soldByMonth'=>$soldByMonth->get()
            ]
        ]);
    }
}
