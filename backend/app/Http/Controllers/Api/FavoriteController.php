<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'client_token' => 'required|string',
        ]);

        $favorites = Favorite::with('product.category')
            ->where('client_token', $validated['client_token'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $favorites,
        ]);
    }

    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'client_token' => 'required|string',
            'product_id' => 'required|exists:products,id',
        ]);

        $favorite = Favorite::where('client_token', $validated['client_token'])
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($favorite) {
            $favorite->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produit retiré des favoris.',
                'is_favorite' => false,
            ]);
        }

        Favorite::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Produit ajouté aux favoris.',
            'is_favorite' => true,
        ]);
    }
}

