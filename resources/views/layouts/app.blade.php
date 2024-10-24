{{-- resources/views/layouts/main.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>

<body>
    @include('layouts.partials.navbar_top')
    <div class="container-fluid">
        @include('layouts.partials.alerts')
        @yield('content')
    </div>
    {{-- @includeIf('layouts.partials.navbar_bottom') --}}
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script>
        function loadNotifications() {
            $.ajax({
                url: "{{ route('notifications.unread') }}",
                method: 'GET',
                success: function(data) {
                    let unreadCount = data.length;
                    $('#unread-count').text(unreadCount);

                    let notificationList = $('#notifications-list');
                    notificationList.empty();

                    if (data.length > 0) {
                        data.forEach(notification => {
                            notificationList.append(`
                            <div class="dropdown-item">
                                <a href="${notification.url}">${notification.subject}</a>
                                <p>${notification.message}</p>
                            </div>
                        `);
                        });
                    } else {
                        notificationList.append('<p class="text-muted">Nessuna nuova notifica.</p>');
                    }
                }
            });
        }

        // Carica le notifiche ogni 10 secondi
        setInterval(loadNotifications, 10000);
        loadNotifications(); // Prima chiamata immediata
    </script>
</body>

</html>
