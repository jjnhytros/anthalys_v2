<form
    action="{{ isset($alcoholic) ? route('productions.alcoholics.update', $alcoholic) : route('productions.alcoholics.store') }}"
    method="POST">
    @csrf
    @if (isset($alcoholic))
        @method('PUT')
    @endif

    <div class="form-group">
        <label for="name">Nome della Bevanda Alcolica</label>
        <input type="text" class="form-control" id="name" name="name"
            value="{{ $alcoholic->name ?? old('name') }}" required>
    </div>

    <div class="form-group">
        <label for="batch_size">Quantità (L)</label>
        <input type="number" class="form-control" id="batch_size" name="batch_size"
            value="{{ $alcoholic->batch_size ?? old('batch_size') }}" required>
    </div>

    <div class="form-group">
        <label for="malt_type">Tipo di Malto (opzionale)</label>
        <input type="text" class="form-control" id="malt_type" name="malt_type"
            value="{{ $alcoholic->malt_type ?? old('malt_type') }}">
    </div>

    <div class="form-group">
        <label for="hop_type">Tipo di Luppolo (opzionale)</label>
        <input type="text" class="form-control" id="hop_type" name="hop_type"
            value="{{ $alcoholic->hop_type ?? old('hop_type') }}">
    </div>

    <div class="form-group">
        <label for="yeast_type">Tipo di Lievito (opzionale)</label>
        <input type="text" class="form-control" id="yeast_type" name="yeast_type"
            value="{{ $alcoholic->yeast_type ?? old('yeast_type') }}">
    </div>

    <div class="form-group">
        <label for="water_source">Fonte d'Acqua</label>
        <input type="text" class="form-control" id="water_source" name="water_source"
            value="{{ $alcoholic->water_source ?? old('water_source') }}" required>
    </div>

    <div class="form-group">
        <label for="production_phase">Fase di Produzione</label>
        <input type="text" class="form-control" id="production_phase" name="production_phase"
            value="{{ $alcoholic->production_phase ?? old('production_phase') }}" required>
    </div>

    <button type="submit" class="btn btn-primary">Salva</button>
</form>
