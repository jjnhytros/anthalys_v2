<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Resource;
use Illuminate\Http\Request;
use App\Models\ResourceTransfer;

class ResourceTransferController extends Controller
{
    public function transfer(Request $request)
    {
        // Validazione dei dati
        $request->validate([
            'source_district_id' => 'required|exists:districts,id',
            'target_district_id' => 'required|exists:districts,id|different:source_district_id',
            'resource_id' => 'required|exists:resources,id',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        // Recupera la risorsa e i distretti
        $resource = Resource::find($request->resource_id);
        $sourceDistrict = District::find($request->source_district_id);
        $targetDistrict = District::find($request->target_district_id);

        // Verifica se il distretto di origine ha abbastanza risorse
        if ($resource->quantity < $request->quantity) {
            return back()->withErrors(['error' => 'Quantità insufficiente nel distretto di origine.']);
        }

        // Aggiorna le quantità nei distretti
        $resource->quantity -= $request->quantity;
        $resource->save();

        // Aggiungi la risorsa al distretto di destinazione
        $targetResource = Resource::firstOrCreate([
            'name' => $resource->name,
            'district_id' => $targetDistrict->id,
        ], [
            'quantity' => 0,
            'daily_production' => 0,
            'consumed' => 0,
            'unit' => $resource->unit,
        ]);

        $targetResource->quantity += $request->quantity;
        $targetResource->save();

        // Registra il trasferimento
        ResourceTransfer::create([
            'source_district_id' => $sourceDistrict->id,
            'target_district_id' => $targetDistrict->id,
            'resource_id' => $resource->id,
            'quantity' => $request->quantity,
        ]);

        return back()->with('success', 'Trasferimento di risorse completato con successo!');
    }
}
