<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\PortfolioFootage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    /**
     * Display a listing of portfolios
     */
    public function index()
    {
        $portfolios = Portfolio::latest()->paginate(10);
        return view('admin.portfolio.index', compact('portfolios'));
    }

    /**
     * Show the form for creating a new portfolio
     */
    public function create()
    {
        $categories = ['web', 'mobile', 'design'];
        return view('admin.portfolio.create', compact('categories'));
    }

    /**
     * Store a newly created portfolio
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'detailed_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'price_6months' => 'nullable|numeric|min:0',
            'price_1year' => 'nullable|numeric|min:0',
            'price_2years' => 'nullable|numeric|min:0',
            'category' => 'required|in:web,mobile,design',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,archived',
            'festive_discount_enabled' => 'nullable|boolean',
            'festive_discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('portfolios/thumbnails', 'public');
            $validated['thumbnail'] = $path;
        }

        $portfolio = Portfolio::create($validated);

        return redirect()->route('admin.portfolio.edit', $portfolio)
            ->with('success', 'Portfolio created successfully. Now add footages!');
    }

    /**
     * Show the form for editing a portfolio
     */
    public function edit(Portfolio $portfolio)
    {
        $categories = ['web', 'mobile', 'design'];
        $footages = $portfolio->footages()->orderBy('display_order')->get();
        return view('admin.portfolio.edit', compact('portfolio', 'categories', 'footages'));
    }

    /**
     * Update the portfolio
     */
    public function update(Request $request, Portfolio $portfolio)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'detailed_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'price_6months' => 'nullable|numeric|min:0',
            'price_1year' => 'nullable|numeric|min:0',
            'price_2years' => 'nullable|numeric|min:0',
            'category' => 'required|in:web,mobile,design',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,archived',
            'festive_discount_enabled' => 'nullable|boolean',
            'festive_discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($portfolio->thumbnail && file_exists(storage_path('app/public/' . $portfolio->thumbnail))) {
                unlink(storage_path('app/public/' . $portfolio->thumbnail));
            }
            $path = $request->file('thumbnail')->store('portfolios/thumbnails', 'public');
            $validated['thumbnail'] = $path;
        }

        $portfolio->update($validated);

        return redirect()->back()->with('success', 'Portfolio updated successfully!');
    }

    /**
     * Delete a portfolio
     */
    public function destroy(Portfolio $portfolio)
    {
        // Delete thumbnail
        if ($portfolio->thumbnail && file_exists(storage_path('app/public/' . $portfolio->thumbnail))) {
            unlink(storage_path('app/public/' . $portfolio->thumbnail));
        }

        // Delete footages
        foreach ($portfolio->footages as $footage) {
            if (file_exists(storage_path('app/public/' . $footage->media_path))) {
                unlink(storage_path('app/public/' . $footage->media_path));
            }
            $footage->delete();
        }

        $portfolio->delete();

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Portfolio and all footages deleted successfully!');
    }

    /**
     * Add footage to portfolio
     */
    public function addFootage(Request $request, Portfolio $portfolio)
    {
        $validated = $request->validate([
            'type' => 'required|in:photo,video',
            'media' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,webm|max:20480',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $path = $request->file('media')->store('portfolios/footages', 'public');

        $nextOrder = $portfolio->footages()->max('display_order') ?? 0;

        PortfolioFootage::create([
            'portfolio_id' => $portfolio->id,
            'type' => $validated['type'],
            'media_path' => $path,
            'title' => $validated['title'] ?? null,
            'description' => $validated['description'] ?? null,
            'display_order' => $nextOrder + 1,
        ]);

        return redirect()->back()->with('success', 'Footage added successfully!');
    }

    /**
     * Delete footage
     */
    public function deleteFootage(PortfolioFootage $footage)
    {
        $portfolio = $footage->portfolio;

        if (file_exists(storage_path('app/public/' . $footage->media_path))) {
            unlink(storage_path('app/public/' . $footage->media_path));
        }

        $footage->delete();

        return redirect()->back()->with('success', 'Footage deleted successfully!');
    }

    /**
     * Reorder footages
     */
    public function reorderFootages(Request $request, Portfolio $portfolio)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:portfolio_footages,id',
        ]);

        foreach ($validated['order'] as $index => $footageId) {
            PortfolioFootage::where('id', $footageId)->update([
                'display_order' => $index + 1,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Footages reordered successfully!']);
    }
}
