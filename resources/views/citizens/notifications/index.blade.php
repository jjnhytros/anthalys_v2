@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="notifications-panel">
            <div class="dropdown">
                <a class="notifications-dropdown-toggle" href="#" id="notificationsDropdown" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-bell"></i>
                    <span class="badge notifications-badge">{{ $unreadCount }}</span>
                </a>
                <div class="dropdown-menu notifications-dropdown-menu" aria-labelledby="notificationsDropdown">
                    <h6 class="notifications-dropdown-menu-header">Notifiche</h6>
                    <ul class="notifications-list">
                        @foreach ($notifications as $notification)
                            <li class="notifications-list-item">
                                <a href="{{ $notification->url }}"
                                    class="{{ $notification->status == 'unread' ? 'notifications-bold' : '' }}">
                                    {{ $notification->message }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
