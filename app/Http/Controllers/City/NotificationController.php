<?php

namespace App\Http\Controllers\City;

use App\Models\CLAIR;
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

        // Filtro in base allo stato delle notifiche
        if ($filter === 'unread') {
            $query->where('status', 'unread');
        } elseif ($filter === 'read') {
            $query->where('status', 'read');
        } elseif ($filter === 'archived') {
            $query->where('status', 'archived');
        }

        $notifications = $query->orderBy('created_at', 'desc')->get();

        // Registra l'attività di visualizzazione delle notifiche
        CLAIR::logActivity(
            'C',
            'index',
            'Visualizzazione delle notifiche con filtro',
            ['filter' => $filter, 'total_notifications' => $notifications->count()]
        );

        return view('citizens.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Message::findOrFail($id);

        if ($notification->recipient_id !== Auth::user()->citizen->id()) {
            abort(403);
        }

        $notification->update(['status' => 'read']);

        // Registra l'attività di lettura della notifica
        CLAIR::logActivity(
            'A',
            'markAsRead',
            'Notifica segnata come letta',
            ['notification_id' => $id]
        );

        return redirect()->back()->with('success', 'Notifica segnata come letta.');
    }

    public function archive($id)
    {
        $notification = Message::findOrFail($id);

        if ($notification->recipient_id !== Auth::user()->citizen->id()) {
            abort(403);
        }

        $notification->update(['status' => 'archived']);

        // Registra l'attività di archiviazione della notifica
        CLAIR::logActivity(
            'A',
            'archive',
            'Notifica archiviata',
            ['notification_id' => $id]
        );

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

        // Registra l'attività di caricamento delle notifiche non lette
        CLAIR::logActivity(
            'I',
            'loadUnreadNotifications',
            'Caricamento delle notifiche non lette',
            ['unread_count' => $unreadNotifications->count()]
        );

        return response()->json($unreadNotifications);
    }
}
