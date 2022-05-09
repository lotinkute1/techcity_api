<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = Message::all();
        return response()->json([
            'code'=>200,
            'message'=>'get all messages',
            'data'=>$messages
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $message = $request->all();
        $conversation = Conversation::find($request['conversation_id']);
        // conversation is exist
        if($conversation){
            $response = Message::create([
                'message_text'=> $message['message_text'],
                'conversation_id'=> $message['conversation_id'],
                'user_id'=> $request->user()->id,
            ]);
            return response()->json([
                'code'=>201,
                'message'=>'message created',
                'data'=>$response
            ],201);
        }
        $newConversation = Conversation::create([
            'conversation_name'=>'boxchat',
            'userone_id'=>$request->user()->id,
            'usertwo_id'=>$request['user2_id'],
        ]);
        // conversation not exist

        $response = Message::create([
            'message_text'=> $message['message_text'],
            'conversation_id'=> $newConversation['id'],
            'user_id'=> $request->user()->id,
        ]);
        return response()->json([
            'code'=>201,
            'message'=>'message and newConversation created ',
            'data'=>$response
        ],201);
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
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function findByConversationId($conversation_id)
    {
        $messages = Message::where('conversation_id','=',$conversation_id)->get();
        if(count($messages)>0){
            return response()->json([
                'code'=>200,
                'message' =>'get messages by conversation id',
                'data'=>$messages
            ]);
        };
        return response()->json([
            'code'=>404,
            'messages' => 'messages not found'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
