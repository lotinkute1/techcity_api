<?php

namespace App\Http\Controllers;

use App\Models\DiscountDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class discountDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discounts = DiscountDetail::selectRaw('discount_details.*,products.name as discount_product_name')->leftJoin('products','products.id','=', 'discount_details.product_id')->get();


        return Response()->json([
            'status' => '200',
            'message' => 'get DiscountDetail list',
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
            'discount_percent' => 'required|int',
            'product_id' => 'required|int',
            'discount_id' => 'required|int',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                'message' => $validator->errors()
            ], 403);
        }

        $response = DiscountDetail::create([
            'discount_percent' => $request['discount_percent'],
            'product_id' => $request['product_id'],
            'discount_id' => $request['discount_id'],
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
    public function checkDiscountCode($code)
    {

        $discountDetail = DiscountDetail::where('discount_code','=',$code)->first();
        $saleOffProduct = Product::find($discountDetail['product_id']);
        if($discountDetail){
            return response()->json([
                'code' => 201,
                'message' =>'discount code valid',
                'data' =>array_merge(['discount'=>$discountDetail],['product'=>$saleOffProduct] ),
            ],201);
        }
        return response()->json([
            'code' => 404,
            'message' =>'discount code invalid',
        ],404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $discount = DiscountDetail::find($id);
        if ($discount) {
            return response()->json([
                'code' => 200,
                'message' => 'get Discount Detail by id',
                'data' => $discount
            ], 200);
        }
        return response()->json([
            'code' => 404,
            'message' => 'Discount Detail not found',
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
        $discount = DiscountDetail::find($id);
        if($discount){
            $discount->update($request->all());
            return response()->json([
                'status' => 200,
                'message' => 'update Discount Detail successfully',
                'data'=>$discount
            ]);
        }
        return response()->json([
            'status' => 404,
            'message' => 'Discount Detail not found'
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
        $discount = DiscountDetail::find($id);
        if($discount){
            $discount->delete();

            return response()->json([
                'status' => 203,
                'message' => 'delete Discount Detail successfully',
            ]);
        }else{

            return response()->json([
                'status' => 404,
                'message' => 'Discount Detail not found',
            ]);
        }
    }
}
