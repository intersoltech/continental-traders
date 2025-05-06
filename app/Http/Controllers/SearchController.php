<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // or any other model you're searching

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');

        // Example search logic (search in `name` and `description` of `products`)
        $results = Product::where('name', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%")
                          ->get();

        return view('search.results', compact('results', 'query'));
    }
}
