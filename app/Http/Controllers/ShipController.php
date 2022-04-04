<?php

namespace App\Http\Controllers;

use App\Models\Ship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get all ships
        $ship = Ship::all();
        return response()->json([
            'code'=>200,
            'message'=>'ships list',
            'data'=> $ship
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //Tạo ship 
        $validator = Validator::make($request->all(),[
            'ship_company' => 'required|unique:ships,ship_company',
            'ship_price' => 'required|numeric',
            'unit' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 403,
                'message' => $validator->errors()
            ]);
        };
        $response = Ship::create([
            'ship_company' => $request['ship_company'],
            'ship_price' => $request['ship_price'],
            'unit' => $request['unit']
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
        //Lấy Ship theo Id
        $ship = Ship::find($id);
        if($ship){
            return response()->json([
                'code'=>200,
                'message'=> 'get ship',
                'data'=>$ship
            ],200);
        }
        return response()->json([
            'code'=>404,
            'message'=> 'ship not found',
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
        //Update theo id
        $ship = Ship::find($id);
        if ($ship) {
            $ship->update($request->all());
            return response()->json([
                'code' => 200,
                'message' => 'updated successfully',
                'data' => $ship
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
        $ship = Ship::find($id);
        if ($ship) {
            $ship->delete();
            return response()->json([
                'code' => 204,
                'message' => 'deleted ship',
            ]);
        }
        return response()->json([
            'code' => 404,
            'message' => 'ship not found',
        ]);         
    }
}
