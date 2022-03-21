<?php

namespace App\Http\Controllers;

use App\Models\Category as ModelsCategory;
use Illuminate\Http\Request;

class Category extends Controller
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
        $request->validate([
            'category_name' => 'required|string',
            'status' => 'required|min:0|max:1',
        ]);
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
        //
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
}
