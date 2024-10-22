<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;
use App\Models\ResourceTransfer;

class ResourceTransferController extends Controller
{
    public function transfer(Request $request)
    {
        $sourceDistrict = District::find($request->input('source_district_id'));
        $targetDistrict = District::find($request->input('target_district_id'));
        $resourceName = $request->input('resource_name');
        $quantity = $request->input('quantity');

        if ($sourceDistrict->transferResource($targetDistrict, $resourceName, $quantity)) {
            // Registra lo scambio nella tabella resource_transfers
            ResourceTransfer::create([
                'source_district_id' => $sourceDistrict->id,
                'target_district_id' => $targetDistrict->id,
                'resource_name' => $resourceName,
                'quantity' => $quantity,
            ]);

            return redirect()->back()->with('success', 'Trasferimento riuscito!');
        }

        return redirect()->back()->with('error', 'Trasferimento fallito. Risorse insufficienti.');
    }
}
