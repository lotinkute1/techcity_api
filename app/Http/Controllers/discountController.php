<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\DiscountDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class discountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discounts = Discount::all();
        return Response()->json([
            'status' => '200',
            'message' => 'get discount list',
            'data' => $discounts
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
            'discount_name' => 'required|string|unique:discounts,discount_name',
            'start_day' => 'required|date',
            'end_day' => 'required|date',
            'status' => 'required|min:0|max:1',
            'discount_img' => 'required|string',
            'start_day' => ['date_format:Y/m/d'],
            'end_day' => ['date_format:Y/m/d','after_or_equal:start_day']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                'message' => $validator->errors()
            ], 403);
        }

        $response = Discount::create([
            'discount_name' => $request['discount_name'],
            'start_day' => $request['start_day'],
            'end_day' => $request['end_day'],
            'status' => $request['status'],
            'discount_img' => $request['discount_img'],

        ]);
        return response()->json([
            'status' => 201,
            'message' => 'create discount successfully',
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
        $discount = Discount::find($id);
        if ($discount) {
            return response()->json([
                'code' => 200,
                'message' => 'get discount by id',
                'data' => $discount
            ], 200);
        }
        return response()->json([
            'code' => 404,
            'message' => 'discount not found',
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function findByName($name){
        $result= Discount::where('discount_name','like','%'.$name.'%')->get();
        if(count($result)>0){
            return response()->json([
                'code'=> 200,
                'message' => 'get discount by name',
                'data' =>$result
            ],200);
        }
        return response()->json([
            'code' => 404,
            'message' => 'not found'
        ],404);
    }
    public function destroy($id)
    {
        $discount = Discount::find($id);
        if($discount){
            DiscountDetail::where('discount_id','=',$id)->delete();
            $discount->delete();

            return response()->json([
                'status' => 203,
                'message' => 'delete discount successfully',
            ]);
        }else{

            return response()->json([
                'status' => 404,
                'message' => 'discount not found',
            ]);
        }
    }
    public function updateDiscount($id,Request $request){
        $discount = Discount::find($id);
        if($discount){
            $discount->update($request->all());
            return response()->json([
                'status' => 200,
                'message' => 'update discount successfully',
                'data'=>$discount
            ]);
        }
        return response()->json([
            'status' => 404,
            'message' => 'discount not found'
        ]);
    }
}
