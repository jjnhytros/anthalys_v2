@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Riscatta i tuoi punti fedeltà</h1>
        <p>Hai {{ $loyaltyPoints ? $loyaltyPoints->points : 0 }} punti fedeltà disponibili.</p>

        <ul>
            @foreach ($rewards as $reward)
                <li>
                    {{ $reward->name }} - {{ $reward->points_required }} punti
                    <form action="{{ route('rewards.redeem', $reward) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary">Riscatta</button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
