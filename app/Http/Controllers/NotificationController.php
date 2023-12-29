<?php

namespace App\Http\Controllers;

use App\Events\PresenceEvent;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Notification;
// use App\Events\PresenceEvent;
class NotificationController extends Controller
{
    //
    public function get_notifications()
    {
         return Inertia::render('Notif/Index', [
            'notifs' => Notification::with('user:id')->get(),
        ]);
    }
    public function store(Request $request)
    {
      $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
        $user = $request->user();

        $notif = $request->user()->notif()->create($validated);


        $notification = Notification::find($notif->id);



        broadcast(new PresenceEvent($user, $notification));
     

     return back()->with('success', 'Notification sent successfully');
    }

}
