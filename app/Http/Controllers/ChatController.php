<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Chat;
use App\Models\User;
use App\Events\UsersChats;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    //
    public function index($recipientId)
    {
        $userList = User::findOrFail($recipientId);
        $users = User::orderBy('created_at', 'desc')->get();

        $chats = Chat::where(function ($query) use ($recipientId) {
            $query->where('sender_id', auth()->user()->id)
                ->where('recipient_id', $recipientId);
        })->orWhere(function ($query) use ($recipientId) {
            $query->where('sender_id', $recipientId)
                ->where('recipient_id', auth()->user()->id);
        })->with(['sender', 'recipient'])->orderBy('created_at', 'asc')->get();

        return Inertia::render('Chats/Index', [
            'userList' => $userList,
            'chats' => $chats,
            'users' => $users,
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'recipient_id' => 'required|exists:users,id',
        ]);

        // Inject the authenticated user
        $user = $request->user();

        // $user_id = auth()->user();



        // Find the recipient
        $recipient = User::findOrFail($validated['recipient_id']);

        // Use Eloquent relationships to create the chat message
        $chatMessage = $recipient->receivedChats()->create([
            'user_id' => $user->id,
            'sender_id' => $user->id,
            'message' => $validated['message'],
        ]);

        if ($chatMessage->chat) {
        $chatMessage->chat->touch();
    }
        // Broadcasting the message to the frontend using Laravel Echo
        broadcast(new UsersChats($user, $chatMessage))->toOthers();

        return redirect()->back();
        // return $this->get_users();
    }

    // public function get_users()
    // {
    //     $users = User::with('chats')->get();

    //     return response()->json(['users' => $users]);
    // }
    public function get_users()
{

   
   $authUser = Auth::user();


    // $users = User::with(['chats' => function ($query) {
    //     $query->orderBy('created_at', 'asc')->get();
    // }])->get();

    //   $users = User::with(['chats' => function ($query) use ($authUser) {
   
    //     $query->where('recipient_id', $authUser->id);
    // }])->get();

     $users = User::with(['chats' => function ($query) use ($authUser) {
        $query->where('recipient_id', $authUser->id);
    }])
    ->orderBy('updated_at', 'desc')
    ->get();

    

    $usersWithMessages = $users->map(function ($user) {

  
        $user->latestMessage = $user->chats->last();

        return $user;
    });

    return response()->json(['users' =>   $usersWithMessages]);
}


}
