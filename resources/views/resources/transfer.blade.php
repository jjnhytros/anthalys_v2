@extends('layouts.app')

@section('content')
    <h1>Trasferimento di Risorse tra Distretti</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('resource.transfer') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="source_district_id">Distretto di Origine:</label>
            <select name="source_district_id" id="source_district_id" class="form-control">
                @foreach ($districts as $district)
                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="target_district_id">Distretto Destinatario:</label>
            <select name="target_district_id" id="target_district_id" class="form-control">
                @foreach ($districts as $district)
                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="resource_name">Risorsa da Trasferire:</label>
            <select name="resource_name" id="resource_name" class="form-control">
                <option value="Energia">Energia</option>
                <option value="Acqua">Acqua</option>
                <option value="Cibo">Cibo</option>
            </select>
        </div>

        <div class="form-group">
            <label for="quantity">Quantità da Trasferire:</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Trasferisci Risorse</button>
    </form>
@endsection
