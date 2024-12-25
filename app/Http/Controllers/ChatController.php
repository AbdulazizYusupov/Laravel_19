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
        'text' => 'required_without:file',
        'file' => 'nullable|mimes:jpeg,png,pdf,docx,txt,doc,mp4,mp3|max:5000',
    ]);

    $sender = auth()->user()->name;
    $filePath = null;

    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filePath = $file->store('messages', 'public');
    }

    $Message = Message::create([
        'text' => $request->text,
        'chat_id' => $id,
        'sender' => $sender,
        'file' => $filePath,
    ]);

    broadcast(new MessageEvent($Message));
    return back();
}

    public function logout()
    {
        $user = User::find(auth()->id());
        if ($user) {
            $user->status = 0;
            $user->save();
        }

        auth()->logout();
        return redirect('/');
    }
}
