<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get rating
        $rating = Rating::all();
        return response()->json([
            'code'=>200,
            'message'=>'rating list',
            'data'=> $rating
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //Create rating
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'raiting_stars' => 'required|numeric|min:0|max:5',
<<<<<<< HEAD
            'comment_content' => 'string'
=======
            'comment_content' => ''
>>>>>>> master
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 403,
                'message' => $validator->errors()
            ],403);
        };

        $response = Rating::create([
            'user_id' => $request->user()->id,
            'product_id' => $request['product_id'],
            'raiting_stars' => $request['raiting_stars'],
            'comment_content' => $request['comment_content']
        ]);
        return response()->json([
            'status' => 201,
            'message' => 'create rating successfully',
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
        //Lấy rating theo product id
        $rating = Rating::where('product_id','=',$id)->get();
        if($rating){
            return response()->json([
                'code'=>200,
                'message'=> 'get rating by product Id',
                'data'=>$rating
            ],200);
        }
        return response()->json([
            'code'=>404,
            'message'=> 'rating not found',
        ],404);
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
        //Update Rating
        $rating = Rating::find($id);
        if ($rating) {
            $rating->update($request->all());
            return response()->json([
                'code' => 200,
                'message' => 'updated rating successfully',
                'data' => $rating
            ]);
        }

        return response()->json([
            'code' => 404,
            'message' => 'rating not found',
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
        $rating = Rating::find($id);
        if ($rating) {
            $rating->delete();
            return response()->json([
                'code' => 204,
                'message' => 'deleted rating',
            ]);
        }
        return response()->json([
            'code' => 404,
            'message' => 'rating not found',
        ]);
    }
}
