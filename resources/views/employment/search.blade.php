@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Cerca Occupazioni</h1>

        <!-- Filtri Avanzati -->
        <div class="filters mb-4">
            <form action="{{ route('employment.search') }}" method="GET" class="form-inline">
                <div class="form-group mx-2">
                    <label for="stress_level" class="mr-2">Livello di Stress:</label>
                    <select name="stress_level" class="form-control">
                        <option value="">Tutti</option>
                        <option value="low">Basso</option>
                        <option value="medium">Medio</option>
                        <option value="high">Alto</option>
                    </select>
                </div>

                <div class="form-group mx-2">
                    <label for="skill" class="mr-2">Abilità Richiesta:</label>
                    <select name="skill" class="form-control">
                        <option value="">Tutte</option>
                        @foreach ($skills as $skill)
                            <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary mx-2">Filtra</button>
            </form>
        </div>

        <!-- Lista Occupazioni -->
        <div class="row">
            @foreach ($occupations as $occupation)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $occupation->title }}</h5>
                            <p class="card-text">{{ $occupation->description }}</p>
                            <p class="card-text"><strong>Salario:</strong> {{ $occupation->salary }} Athel</p>
                            <p class="card-text"><strong>Livello di Stress:</strong>
                                {{ ucfirst($occupation->stress_level) }}</p>

                            <!-- Barra di Progresso della Reputazione -->
                            @if ($citizen->reputation < $occupation->required_reputation)
                                <div class="progress mb-2">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ ($citizen->reputation / $occupation->required_reputation) * 100 }}%;"
                                        aria-valuenow="{{ $citizen->reputation }}" aria-valuemin="0"
                                        aria-valuemax="{{ $occupation->required_reputation }}">
                                        {{ $citizen->reputation }} / {{ $occupation->required_reputation }}
                                    </div>
                                </div>
                                <small class="text-muted">Reputazione richiesta:
                                    {{ $occupation->required_reputation }}</small>
                            @endif

                            <!-- Anteprima della Candidatura -->
                            <div class="mt-3">
                                <h6>Probabilità di Successo</h6>
                                <p class="text-muted">In base alle tue qualifiche, hai il
                                    <strong>{{ $occupation->success_chance }}%</strong> di successo.</p>
                            </div>

                            <!-- Pulsante di Candidatura -->
                            <form action="{{ route('employment.apply', $occupation->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="citizen_id" value="{{ $citizen->id }}">
                                <button type="submit" class="btn btn-success">Candidati</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
