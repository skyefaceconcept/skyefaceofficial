<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;

class ShoppingController extends Controller
{
    /**
     * Display all published portfolios
     */
    public function index(Request $request)
    {
        $query = Portfolio::published()->with('footages');

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Search - use AND logic to preserve category filter
        if ($request->has('search') && $request->search) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm);
            });
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch($sort) {
            case 'price-low':
                $query->orderBy('price', 'asc');
                break;
            case 'price-high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            default:
                $query->latest();
        }

        // Paginate with optimized query
        $portfolios = $query->paginate(12);
        $categories = ['web', 'mobile', 'design'];

        return view('shop.index', compact('portfolios', 'categories'));
    }

    /**
     * Display a single portfolio
     */
    public function show(Portfolio $portfolio)
    {
        // Only show published portfolios
        if ($portfolio->status !== 'published') {
            abort(404);
        }

        // Increment view count without loading full model (non-blocking)
        Portfolio::where('id', $portfolio->id)->increment('view_count');

        // Eager load footages and related portfolios
        $footages = $portfolio->footages()->orderBy('display_order')->get();
        $relatedPortfolios = Portfolio::published()
            ->where('category', $portfolio->category)
            ->where('id', '!=', $portfolio->id)
            ->orderBy('view_count', 'desc')
            ->limit(4)
            ->get();

        return view('shop.show', compact('portfolio', 'footages', 'relatedPortfolios'));
    }
}
