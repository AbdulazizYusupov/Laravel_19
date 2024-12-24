<?php

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->user()->id)->get();
        return view('chat', ['users' => $users]);
    }
    public function show($id)
    {
        $user = User::find($id);
        $users = User::where('id', '!=', auth()->user()->id)->get();
        $chat = Chat::where(function ($query) use ($user) {
            $query->where('from_id', auth()->user()->id)->where('to_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('from_id', $user->id)->where('to_id', auth()->user()->id);
        })->first();
        if (is_null($chat)) {
            $chat = Chat::create([
                'from_id' => auth()->user()->id,
                'to_id' => $user->id
            ]);
        }
        $models = Message::where('chat_id', $chat->id)->orderBy('created_at', 'desc')->get();
        return view('chat', ['users' => $users, 'chat' => $chat, 'bro' => $user, 'models' => $models]);
    }
    public function create(Request $request, $id)
    {
        $request->validate([
            'text' => 'required'
        ]);
        $sender = auth()->user()->name;
        $Message = Message::create([
            'text' => $request->text,
            'chat_id' => $id,
            'sender' => $sender
        ]);
        broadcast(new MessageEvent($Message));
        return back();
    }
}
