<?php

namespace App\Http\Controllers\Market;

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

        ProductReview::create($validatedData);

        return redirect()->back()->with('success', 'Recensione inviata con successo!');
    }
}
