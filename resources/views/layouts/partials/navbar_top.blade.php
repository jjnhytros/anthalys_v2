{{-- resources/views/layouts/partials/navbar_top.blade.php --}}
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">TV Collection</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    @auth
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('messages.inbox') }}">Messages</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contacts.index') }}">Contacts</a>
                </li>
            </ul>
        @endauth
        {{--  --}}
    </div>
</nav>
