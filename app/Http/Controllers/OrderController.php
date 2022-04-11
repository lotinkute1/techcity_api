<?php

namespace App\Http\Controllers;

use App\Mail\OrderMail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order = Order::all();
        return response()->json([
            'status' => '200',
            'message' => 'order list',
            'data' => $order
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|int',
            'recipient_name' => 'required|string',
            'recipient_address' => 'required|string',
            'recipient_phone_number' => 'required|string',
            'status' => 'required|min:0|max:1',
            'total' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                'message' => $validator->errors()
            ], 403);
        }

        $order = Order::create([
            'user_id' => $request['user_id'],
            'recipient_name' => $request['recipient_name'],
            'recipient_address' => $request['recipient_address'],
            'recipient_phone_number' => $request['recipient_phone_number'],
            'status' => $request['status'],
            'total' => $request['total'],
        ]);
        $orderDetails = [];
        foreach ($request['order_detail'] as $key => $orderDetail) {
            $orderDetails[$key] = OrderDetail::create([
                'number' => $orderDetail['number'],
                'order_id' => $order['id'],
                'price' => $orderDetail['price'],
                'product_id' => $orderDetail['product_id'],
                'status' => 1,
                'product_name' => $orderDetail['product_name']
            ]);
        }
        // $user = User::find($request['user_id']);
        $user = $request->user();
        Mail::send(new OrderMail(['email' => $user->email]));
        return response()->json([
            'status' => 201,
            'message' => 'create order successfully',
            'data' => array_merge($order,['orderDetail' => $orderDetails])
        ], 201);
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
        $Order = Order::find($id);
        if ($Order) {
            return response()->json([
                'code' => 200,
                'message' => 'get Order by id',
                'data' => $Order
            ], 200);
        }
        return response()->json([
            'code' => 404,
            'message' => 'Order not found',
        ], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $order = Order::find($id);
        if ($order) {
            $order->update($request->only(['recipient_name', 'recipient_address', 'recipient_phone_number', 'total', 'status']));
            return response()->json([
                'status' => 200,
                'message' => 'update Order successfully',
                'data' => $order
            ]);
        }
        return response()->json([
            'status' => 404,
            'message' => 'order not found'
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
        $order = Order::find($id);
        if ($order) {
            OrderDetail::where('order_id', '=', $id)->delete();
            $order->delete();

            return response()->json([
                'status' => 203,
                'message' => 'delete order successfully',
            ]);
        } else {

            return response()->json([
                'status' => 404,
                'message' => 'order not found',
            ]);
        }
    }
}
