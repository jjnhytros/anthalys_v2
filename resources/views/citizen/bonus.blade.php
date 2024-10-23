@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Bonus di Riciclo di {{ $citizen->name }}</h1>
        <p>Punti totali accumulati: {{ $citizen->recycling_points }}</p>

        <h2>Bonus Disponibili</h2>
        <ul>
            <li>Sconto sulla tassa: {{ $bonuses['discount'] }}%</li>
            <li>Buono Spesa: {{ $bonuses['voucher'] }} AA</li>
        </ul>

        @if ($bonuses['voucher'] > 0)
            <form action="{{ route('citizens.claimVoucher', $citizen->id) }}" method="POST">
                @csrf
                <button class="btn btn-primary">Riscatta Buono Spesa</button>
            </form>
        @else
            <p>Non hai abbastanza punti per riscattare un buono.</p>
        @endif

        @if (session('success'))
            <div class="mt-4 alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mt-4 alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>
@endsection
