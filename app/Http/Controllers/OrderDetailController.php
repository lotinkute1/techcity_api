<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderDetailController extends Controller
{
    public function index()
    {
        $OrderDetail = OrderDetail::all();
        return response()->json([
            'status' => '200',
            'message' => 'OrderDetail list',
            'data'=>$OrderDetail
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
            'number' => 'required|int',
            'order_id' => 'required|int',
            'price' => 'required|numeric',
            'product_id' => 'required|int',
            'status' => 'min:0|max:1',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                'message' => $validator->errors()
            ], 403);
        }
        $product = Product::find($request['product_id']);
        $response = OrderDetail::create([
            'number' => $request['number'],
            'order_id' => $request['order_id'],
            'price' => $request['price'],
            'product_id' => $request['product_id'],
            'product_name' => $product->name
        ]);
        return response()->json([
            'status' => 201,
            'message' => 'create order detail successfully',
            'data' => $response
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
        $Order = OrderDetail::find($id);
        if ($Order) {
            return response()->json([
                'code' => 200,
                'message' => 'get Order detail by id',
                'data' => $Order
            ], 200);
        }
        return response()->json([
            'code' => 404,
            'message' => 'Order detail not found',
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
        $order = OrderDetail::find($id);
        if($order){
            $order->update($request->only(['number','order_id','price','product_id','product_name']));
            return response()->json([
                'status' => 200,
                'message' => 'update Order successfully',
                'data'=>$order
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
        $order = OrderDetail::find($id);
        if($order){
            $order->delete();

            return response()->json([
                'status' => 203,
                'message' => 'delete order detail successfully',
            ]);
        }else{

            return response()->json([
                'status' => 404,
                'message' => 'order not found',
            ]);
        }
    }
}
