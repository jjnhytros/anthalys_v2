<?php

namespace App\Http\Controllers\Resource;

use App\Models\CLAIR;
use Illuminate\Http\Request;
use App\Models\City\District;
use App\Models\Resource\Resource;
use App\Http\Controllers\Controller;
use App\Models\Resource\ResourceTransfer;

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

        // Log dell'attività di trasferimento delle risorse
        CLAIR::logActivity('I', 'transfer', 'Trasferimento di risorse tra distretti', [
            'source_district' => $sourceDistrict->name,
            'target_district' => $targetDistrict->name,
            'resource' => $resource->name,
            'quantity' => $request->quantity,
        ]);

        return back()->with('success', 'Trasferimento di risorse completato con successo!');
    }

    public function redistributeResources()
    {
        $districts = District::with('resources')->get();

        foreach ($districts as $district) {
            foreach ($district->resources as $resource) {
                // Se una risorsa è sotto il livello critico, cerca un distretto con un surplus
                if ($resource->quantity < 100) {
                    $sourceDistrict = $districts->filter(function ($d) use ($resource) {
                        return $d->resources->where('name', $resource->name)->first()->quantity > 500;
                    })->first();

                    if ($sourceDistrict) {
                        $this->transferResource($sourceDistrict, $district, $resource);

                        // Log dell'attività di ridistribuzione automatica delle risorse
                        CLAIR::logActivity('A', 'redistributeResources', 'Ridistribuzione automatica delle risorse', [
                            'source_district' => $sourceDistrict->name,
                            'target_district' => $district->name,
                            'resource' => $resource->name,
                            'quantity' => 100,
                        ]);
                    }
                }
            }
        }
    }

    private function transferResource($sourceDistrict, $targetDistrict, $resource)
    {
        $quantityToTransfer = 100; // Quantità da trasferire

        ResourceTransfer::create([
            'source_district_id' => $sourceDistrict->id,
            'target_district_id' => $targetDistrict->id,
            'resource_id' => $resource->id,
            'quantity' => $quantityToTransfer,
        ]);

        // Aggiorna la quantità di risorse nei distretti
        $sourceResource = $sourceDistrict->resources->where('name', $resource->name)->first();
        $targetResource = $targetDistrict->resources->where('name', $resource->name)->first();

        $sourceResource->quantity -= $quantityToTransfer;
        $targetResource->quantity += $quantityToTransfer;

        $sourceResource->save();
        $targetResource->save();
    }
}
