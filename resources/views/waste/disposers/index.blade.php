@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>I Tuoi Smaltitori Automatici</h1>

        @if ($disposers->isEmpty())
            <p>Non hai ancora acquistato uno smaltitore automatico.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Efficienza</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($disposers as $disposer)
                        <tr>
                            <td>{{ $disposer->type }}</td>
                            <td>{{ $disposer->efficiency }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <h2>Acquista uno Smaltitore Automatico</h2>
        <form action="{{ route('waste_disposers.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="type">Tipo di Smaltitore</label>
                <select id="type" name="type" class="form-control">
                    <option value="Compostatore">Compostatore</option>
                    <option value="Mini-Riciclatore">Mini-Riciclatore</option>
                    <option value="Smaltitore di Metalli">Smaltitore di Metalli</option>
                </select>
            </div>
            <div class="form-group">
                <label for="efficiency">Efficienza (%)</label>
                <input type="number" id="efficiency" name="efficiency" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Acquista</button>
        </form>
    </div>
@endsection
