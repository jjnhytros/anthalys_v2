{{-- resources/views/layouts/partials/footer.blade.php --}}
<footer class="bg-light text-center mt-auto">
    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('messages.welcome') }}</p>
</footer>

<script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
@yield('js')
<script src="{{ asset('js/custom.js') }}"></script>
