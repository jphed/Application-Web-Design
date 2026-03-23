<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::latest()->get();
        return response()->json($quotes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:500',
            'author' => 'required|string|max:100',
        ]);

        $quote = Quote::create($request->all());
        return response()->json($quote, 201);
    }

    public function update(Request $request, $id)
    {
        $quote = Quote::find($id);
        
        if (!$quote) {
            return response()->json(['error' => 'Quote not found'], 404);
        }

        $request->validate([
            'text' => 'required|string|max:500',
            'author' => 'required|string|max:100',
        ]);

        $quote->update($request->all());
        return response()->json($quote);
    }

    public function destroy($id)
    {
        $quote = Quote::find($id);
        
        if (!$quote) {
            return response()->json(['error' => 'Quote not found'], 404);
        }

        $quote->delete();
        return response()->json(['message' => 'Quote deleted successfully']);
    }
}
