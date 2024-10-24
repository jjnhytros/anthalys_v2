@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Configurazione Politiche di Lavoro</h2>
        <form action="{{ route('government.policies.update') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="work_hours_per_day">Ore di Lavoro per Giorno</label>
                <input type="number" name="work_hours_per_day"
                    value="{{ old('work_hours_per_day', $policy->work_hours_per_day) }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="work_days_per_week">Giorni di Lavoro per Settimana</label>
                <input type="number" name="work_days_per_week"
                    value="{{ old('work_days_per_week', $policy->work_days_per_week) }}" class="form-control">
            </div>
            <!-- Inserisci altri campi simili per vacanze, tasse, ecc. -->
            <button type="submit" class="btn btn-primary">Aggiorna</button>
        </form>
    </div>
@endsection
