<?php

namespace App\Http\Controllers\Market;

use App\Models\CLAIR;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Market\ProductReview;

class ProductReviewController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'citizen_id' => 'required|exists:citizens,id',
            'product_id' => 'required|exists:market_products,id',
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:500',
        ]);

        // Crea la recensione del prodotto
        ProductReview::create($validatedData);

        // Registra l'attività utilizzando C.L.A.I.R.
        CLAIR::logActivity('C', 'store', 'Creazione di una recensione del prodotto', [
            'citizen_id' => $validatedData['citizen_id'],
            'product_id' => $validatedData['product_id'],
            'rating' => $validatedData['rating'],
            'feedback' => $validatedData['feedback'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Recensione inviata con successo!');
    }
}
