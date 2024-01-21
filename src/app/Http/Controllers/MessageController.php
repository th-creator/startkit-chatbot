<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    
    public function index() {
        $messages = Message::where('user_id',auth('sanctum')->id())->get();
        return response()->json(['messages'=>$messages],200);
    }

    public function store(Request $request,$id = null)
    {
        $data = $request->validate([
            'user_message' => 'required',
            'ai_message' => 'required',
            'user_id' => 'required',
        ]);
        
        $num_words = explode(" ",$request->user_message);

        $data["num_words"] = count($num_words);


        $message = Message::create($data);

        return response()->json(['message' => 'message saved successfully', 'message' => $message], 200);
    }
}
