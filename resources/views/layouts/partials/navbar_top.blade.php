{{-- resources/views/layouts/partials/navbar_top.blade.php --}}
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Anthalys</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    @auth
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="notifications-panel">
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" id="notificationsDropdown" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                        <span id="unread-count" class="badge badge-danger">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationsDropdown">
                        <h6 class="dropdown-header">Notifiche</h6>
                        <div id="notifications-list">
                            <!-- Qui saranno caricate le notifiche -->
                        </div>
                        <div class="dropdown-footer">
                            <a href="{{ route('notifications.index') }}">Visualizza tutte le notifiche</a>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="mr-auto navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">Messages</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contacts</a>
                </li>
            </ul>
        @endauth
        {{--  --}}
    </div>
</nav>
