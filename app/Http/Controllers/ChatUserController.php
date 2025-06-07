<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;
use Illuminate\Support\Facades\DB;

class ChatUserController extends Controller
{
    public function index()
    {
        $chatUsers = DB::table('chat_user')
            ->join('users', 'chat_user.user_id', '=', 'users.id')
            ->join('chats', 'chat_user.chat_id', '=', 'chats.id')
            ->select('chat_user.*', 'users.usuario as user_name', 'chats.name as chat_name')
            ->get();
        return view('chat_user.index', compact('chatUsers'));
    }

    public function create()
    {
        $users = User::all();
        $chats = Chat::all();
        return view('chat_user.create', compact('users', 'chats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'chat_id' => 'required|exists:chats,id',
        ]);
        // Prevent duplicate
        $exists = DB::table('chat_user')
            ->where('user_id', $request->user_id)
            ->where('chat_id', $request->chat_id)
            ->exists();
        if ($exists) {
            return redirect()->back()->withErrors(['msg' => 'This user is already a participant in the chat.']);
        }
        DB::table('chat_user')->insert([
            'user_id' => $request->user_id,
            'chat_id' => $request->chat_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->route('chat_user.index')->with('success', 'Participant added to chat.');
    }

    public function edit($chat_id, $user_id)
    {
        $chatUser = DB::table('chat_user')
            ->where('chat_id', $chat_id)
            ->where('user_id', $user_id)
            ->first();
        $users = User::all();
        $chats = Chat::all();
        // Si no existe la relación, redirige con error
        if (!$chatUser) {
            return redirect()->route('chat_user.index')->withErrors(['msg' => 'No se encontró la relación chat-usuario.']);
        }
        return view('chat_user.edit', compact('chatUser', 'users', 'chats'));
    }

    public function update(Request $request, $chat_id, $user_id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'chat_id' => 'required|exists:chats,id',
        ]);
        // Prevent duplicate
        $exists = DB::table('chat_user')
            ->where('user_id', $request->user_id)
            ->where('chat_id', $request->chat_id)
            ->where(function ($query) use ($chat_id, $user_id) {
                $query->where('chat_id', '!=', $chat_id)
                    ->orWhere('user_id', '!=', $user_id);
            })
            ->exists();
        if ($exists) {
            return redirect()->back()->withErrors(['msg' => 'This user is already a participant in the chat.']);
        }
        DB::table('chat_user')
            ->where('chat_id', $chat_id)
            ->where('user_id', $user_id)
            ->update([
                'user_id' => $request->user_id,
                'chat_id' => $request->chat_id,
                'updated_at' => now(),
            ]);
        return redirect()->route('chat_user.index')->with('success', 'Participant updated.');
    }

    public function destroy($chat_id, $user_id)
    {
        DB::table('chat_user')
            ->where('chat_id', $chat_id)
            ->where('user_id', $user_id)
            ->delete();
        return redirect()->route('chat_user.index')->with('success', 'Participant removed from chat.');
    }
}
