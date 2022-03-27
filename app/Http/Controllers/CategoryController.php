<?php

namespace App\Http\Controllers;

use App\Models\Category as ModelsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = ModelsCategory::all();
        return response()->json([
            'code'=>200,
            'message'=>'categories list',
            'data'=> $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addCategory(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'category_name' => 'required|string',
            'status' => 'required|min:0|max:1',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 403,
                'message' => $validator->errors()
            ]);
        };
        $response = ModelsCategory::create([
            'category_name' => $request['category_name'],
            'status' => $request['status']
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
        $category= ModelsCategory::find($id);
        if($category){
            return response()->json([
                'status' => '200',
                'message' => 'get category by id',
                'data' => $category
            ]);
        }else{
            return response()->json([
                'status' => '404',
                'message' => 'category not found',
            ]);
        }
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
        $category = ModelsCategory::find($id);
        if($category){
            $category->update($request->all());
            return response()->json([
                'status' => '201',
                'message'=> 'category update succesfully',
                'data'=>$category
            ]);
        }
        return response()->json([
            'status' => '201',
            'message' => 'category not found'
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
        $result= ModelsCategory::find($id);
        if($result){
            $result->delete();
            return response()->json([
                'code'=> 201,
                'message'=>'delete category succesfully'
            ]);
        }
        return response()->json([
            'code'=> 404,
            'message'=>'category not found'
        ]);

    }
    public function getCategoriesByName($name){
        $result= ModelsCategory::where('category_name','like','%'.$name.'%')->get();
        if(count($result)>0){
            return response()->json([
                'code'=> 200,
                'message' => 'get category by name',
                'data' =>$result
            ],200);
        }
        return response()->json([
            'code' => 404,
            'message' => 'category not found'
        ],404);
    }
}
