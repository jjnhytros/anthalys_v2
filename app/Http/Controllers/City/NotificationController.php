<?php

namespace App\Http\Controllers\City;

use App\Models\City\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $query = Message::where('is_notification', true)
            ->where('recipient_id', Auth::user()->citizen->id());

        if ($filter === 'unread') {
            $query->where('status', 'unread');
        } elseif ($filter === 'read') {
            $query->where('status', 'read');
        } elseif ($filter === 'archived') {
            $query->where('status', 'archived');
        }

        $notifications = $query->orderBy('created_at', 'desc')->get();

        return view('citizens.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Message::findOrFail($id);

        if ($notification->recipient_id !== Auth::user()->citizen->id()) {
            abort(403);
        }

        $notification->update(['status' => 'read']);

        return redirect()->back()->with('success', 'Notifica segnata come letta.');
    }

    public function archive($id)
    {
        $notification = Message::findOrFail($id);

        if ($notification->recipient_id !== Auth::user()->citizen->id()) {
            abort(403);
        }

        $notification->update(['status' => 'archived']);

        return redirect()->back()->with('success', 'Notifica archiviata.');
    }

    public function loadUnreadNotifications()
    {
        $unreadNotifications = Message::where('is_notification', true)
            ->where('recipient_id', Auth::user()->citizen->id())
            ->where('status', 'unread')
            ->latest()
            ->limit(5)
            ->get();

        return response()->json($unreadNotifications);
    }
}
