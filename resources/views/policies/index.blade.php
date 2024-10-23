@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Politiche Governative</h1>
        <a href="{{ route('policies.create') }}" class="btn btn-primary">Aggiungi Politica</a>
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Aliquota/Percentuale</th>
                    <th>Stato</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($policies as $policy)
                    <tr>
                        <td>{{ $policy->name }}</td>
                        <td>{{ ucfirst($policy->type) }}</td>
                        <td>{{ $policy->rate }}%</td>
                        <td>{{ $policy->active ? 'Attiva' : 'Non attiva' }}</td>
                        <td>
                            <a href="{{ route('policies.edit', $policy) }}" class="btn btn-sm btn-warning">Modifica</a>
                            <form action="{{ route('policies.destroy', $policy) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Elimina</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
