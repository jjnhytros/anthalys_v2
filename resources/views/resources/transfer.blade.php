@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Trasferimento di Risorse</h1>

        <form action="{{ route('resource.transfer') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="source_district_id">Distretto di Origine</label>
                <select name="source_district_id" class="form-control">
                    @foreach ($districts as $district)
                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="target_district_id">Distretto di Destinazione</label>
                <select name="target_district_id" class="form-control">
                    @foreach ($districts as $district)
                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="resource_id">Risorsa</label>
                <select name="resource_id" class="form-control">
                    @foreach ($districts->first()->resources as $resource)
                        <option value="{{ $resource->id }}">{{ $resource->name }} ({{ $resource->unit }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="quantity">Quantità</label>
                <input type="number" name="quantity" class="form-control" step="0.01" min="0.01" required>
            </div>

            <button type="submit" class="btn btn-primary">Trasferisci Risorsa</button>
        </form>
    </div>
@endsection
