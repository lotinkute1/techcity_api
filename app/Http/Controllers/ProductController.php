<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get all products
        $products = Product::all();
        return response()->json([
            'code'=>200,
            'message'=>'product list',
            'data'=> $products
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //Tạo product
        $validator = Validator::make($request->all(), [

            'category_id' => 'required',
            'name' => 'required|unique:products,name',
            'brand' => 'required',
            'description' => 'required',
            'price' => 'required',
            'stock_amount' => 'required',
            'img' => 'required',
            'img1' => 'required',
            'img2' => 'required',
            'img3' => 'required',
            'img4' => 'required',
            'ship_id' => 'required',
            'user_id' => 'required'

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                'message' => $validator->errors()
            ], 403);
        }
        $response = Product::create([

            'category_id' => $request['category_id'],
            'name' => $request['name'],
            'brand' => $request['brand'],
            'description' => $request['description'],
            'price' => $request['price'],
            'stock_amount' => $request['stock_amount'],
            'img' => $request['img'],
            'img1' => $request['img1'],
            'img2' => $request['img2'],
            'img3' => $request['img3'],
            'img4' => $request['img4'],
            'ship_id' => $request['ship_id'],
            'user_id' => $request['user_id']

        ]);
        return response()->json([
            'status' => 201,
            'message' => 'create product successfully',
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
        //Lấy product theo Id
        $product = Product::find($id);
        if($product){
            return response()->json([
                'code'=>200,
                'message'=> 'get product',
                'data'=>$product
            ],200);
        }
        return response()->json([
            'code'=>404,
            'message'=> 'product not found',
        ],404);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $name
     * @return \Illuminate\Http\Response
     */
    public function productFilter(Request $request)
    {
        //filter products
        if(
            $request['filterType']=='stock_amount'||
            $request['filterType']=='price'||
            $request['filterType']=='category_id'||
            $request['filterType']=='ship_id'||
            $request['filterType']=='user_id'
        ){
            $product = Product::where($request['filterType'], '=', $request['filterVal'])->get();

        }else{

            $product = Product::where($request['filterType'], 'like', '%' . $request['filterVal'] . '%')->get();
        }
        if(count($product)){
            return response()->json([
                'code'=>200,
                'message'=> 'get product by '.$request['filterType'],
                'data'=>$product
            ],200);
        }
        return response()->json([
            'code'=>404,
            'message'=> 'product filter by ' . $request['filterType'] . ' not found',
        ],404);

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
        //Update theo id
        $product = Product::find($id);
        if ($product) {
            $product->update($request->all());
            return response()->json([
                'code' => 200,
                'message' => 'updated successfully',
                'data' => $product
            ]);
        }

        return response()->json([
            'code' => 404,
            'message' => 'product not found',
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
        //Xóa theo id
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            return response()->json([
                'code' => 204,
                'message' => 'deleted product',
            ]);
        }
        return response()->json([
            'code' => 404,
            'message' => 'product not found',
        ]);
    }
}
