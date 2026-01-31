@extends('layouts.buzbox')

@section('content')

@include('partials.navbar')

<!-- Hero Section -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 60px 0; margin-bottom: 50px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: 0; right: 0; width: 400px; height: 400px; background: rgba(255,255,255,0.1); border-radius: 50%; transform: translate(100px, -100px);"></div>
    <div style="position: absolute; bottom: 0; left: 0; width: 300px; height: 300px; background: rgba(255,255,255,0.1); border-radius: 50%; transform: translate(-50px, 100px);"></div>
    
    <div class="container" style="position: relative; z-index: 1;">
        <h1 style="font-size: 3rem; font-weight: 700; margin-bottom: 20px; letter-spacing: -1px;">Portfolio Shop</h1>
        <p style="font-size: 1.2rem; margin-bottom: 30px; opacity: 0.95;">Discover premium web applications, mobile apps, and design packages crafted by professionals</p>
        
        <div class="row" style="gap: 20px; margin-top: 30px;">
            <div class="col-lg-6">
                <div class="input-group" style="box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                    <input type="text" class="form-control" id="search-input" placeholder="Search portfolios, categories..." style="padding: 12px 18px; border: none; font-size: 1rem;">
                    <button class="btn" type="button" id="search-btn" style="background: #667eea; color: white; border: none; padding: 12px 25px;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-lg-6" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <span style="opacity: 0.8; font-size: 0.9rem;">Quick filter:</span>
                <button onclick="filterCategory('web')" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 8px 16px; border-radius: 6px; cursor: pointer; transition: all 0.3s; font-weight: 500;">
                    <i class="fas fa-globe"></i> Web
                </button>
                <button onclick="filterCategory('mobile')" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 8px 16px; border-radius: 6px; cursor: pointer; transition: all 0.3s; font-weight: 500;">
                    <i class="fas fa-mobile-alt"></i> Mobile
                </button>
                <button onclick="filterCategory('design')" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 8px 16px; border-radius: 6px; cursor: pointer; transition: all 0.3s; font-weight: 500;">
                    <i class="fas fa-palette"></i> Design
                </button>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div style="margin-bottom: 40px;"></div>

    <div class="row mb-4">
        <div class="col-md-3">
            <!-- Filters Sidebar -->
            <div class="card mb-3" style="border: 1px solid #e9ecef; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-radius: 8px;">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 8px 8px 0 0; color: white; padding: 20px;">
                    <h5 class="mb-0" style="font-weight: 600;">
                        <i class="fas fa-filter"></i> Filters
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Category Filter -->
                    <div class="mb-4">
                        <h6 class="mb-3" style="font-weight: 600; color: #333;">Category</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="category" id="category-all" value=""
                                   {{ !request('category') ? 'checked' : '' }}>
                            <label class="form-check-label" for="category-all" style="cursor: pointer;">
                                <i class="fas fa-th-large"></i> All Categories
                            </label>
                        </div>
                        @foreach($categories as $cat)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category" id="category-{{ $cat }}"
                                       value="{{ $cat }}" {{ request('category') === $cat ? 'checked' : '' }}>
                                <label class="form-check-label" for="category-{{ $cat }}" style="cursor: pointer;">
                                    @if($cat === 'web')
                                        <i class="fas fa-globe" style="color: #667eea;"></i> Web Apps
                                    @elseif($cat === 'mobile')
                                        <i class="fas fa-mobile-alt" style="color: #764ba2;"></i> Mobile Apps
                                    @else
                                        <i class="fas fa-palette" style="color: #f093fb;"></i> Designs
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <!-- Sort -->
                    <div class="mb-4">
                        <h6 class="mb-3" style="font-weight: 600; color: #333;">Sort By</h6>
                        <select class="form-select form-select-sm" id="sort-select" style="border-radius: 6px; border: 1px solid #ddd;">
                            <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>
                                <i class="fas fa-clock"></i> Latest
                            </option>
                            <option value="price-low" {{ request('sort') === 'price-low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price-high" {{ request('sort') === 'price-high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                        </select>
                    </div>

                    <button class="btn w-100 btn-sm" id="apply-filters" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; font-weight: 600; padding: 10px;">
                        <i class="fas fa-check"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            @if($portfolios->count() > 0)
                <div class="row">
                    @foreach($portfolios as $portfolio)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm" style="border: 1px solid #e9ecef; border-radius: 12px; overflow: hidden; transition: all 0.3s ease;">
                                <!-- Card Image Container -->
                                <div style="height: 220px; background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%); overflow: hidden; position: relative;">
                                    @if($portfolio->thumbnail)
                                        <a href="{{ route('shop.show', $portfolio) }}" class="text-decoration-none">
                                            <img src="{{ asset('storage/' . $portfolio->thumbnail) }}" alt="{{ $portfolio->title }}"
                                                 style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                                        </a>
                                    @else
                                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                            <span class="text-muted"><i class="fas fa-image" style="font-size: 2rem;"></i></span>
                                        </div>
                                    @endif
                                    
                                    <!-- Category Badge -->
                                    @if($portfolio->category === 'web')
                                        <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); position: absolute; top: 12px; right: 12px; padding: 8px 12px; font-size: 0.75rem; border-radius: 6px;">
                                            <i class="fas fa-globe"></i> Web
                                        </span>
                                    @elseif($portfolio->category === 'mobile')
                                        <span class="badge" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); position: absolute; top: 12px; right: 12px; padding: 8px 12px; font-size: 0.75rem; border-radius: 6px;">
                                            <i class="fas fa-mobile-alt"></i> Mobile
                                        </span>
                                    @else
                                        <span class="badge" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); position: absolute; top: 12px; right: 12px; padding: 8px 12px; font-size: 0.75rem; border-radius: 6px; color: #333;">
                                            <i class="fas fa-palette"></i> Design
                                        </span>
                                    @endif

                                    <!-- Festive Discount Badge -->
                                    @if($portfolio->festive_discount_enabled && $portfolio->festive_discount_percentage > 0)
                                        <span class="badge" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%); position: absolute; top: 50%; right: 12px; transform: translateY(-50%); padding: 12px 8px; font-size: 0.7rem; border-radius: 6px; color: white; text-align: center; min-width: 50px; box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);">
                                            <div style="font-weight: 700; font-size: 1.2rem;">{{ (int)$portfolio->festive_discount_percentage }}%</div>
                                            <div style="font-size: 0.65rem; margin-top: 2px;">OFF</div>
                                        </span>
                                    @endif
                                </div>

                                <!-- Card Body -->
                                <div class="card-body d-flex flex-column" style="padding: 20px;">
                                    <h5 class="card-title" style="font-weight: 700; margin-bottom: 10px; color: #333;">
                                        <a href="{{ route('shop.show', $portfolio) }}" class="text-decoration-none text-dark">
                                            {{ $portfolio->title }}
                                        </a>
                                    </h5>
                                    
                                    <p class="card-text text-muted small flex-grow-1" style="margin-bottom: 15px; line-height: 1.5;">
                                        {{ Str::limit($portfolio->description, 80) }}
                                    </p>

                                    <!-- Rating & Stats -->
                                    <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #e9ecef;">
                                        <div style="display: flex; align-items: center; gap: 8px; font-size: 0.9rem;">
                                            <span style="color: #ffc107;">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half-alt"></i>
                                            </span>
                                            <small class="text-muted">(24 reviews)</small>
                                        </div>
                                    </div>

                                    <!-- Price & Views -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            @if($portfolio->festive_discount_enabled && $portfolio->festive_discount_percentage > 0)
                                                <div style="font-size: 0.85rem; text-decoration: line-through; color: #999; margin-bottom: 4px;">
                                                    ₦{{ number_format($portfolio->price, 0) }}
                                                </div>
                                                <span style="font-size: 1.3rem; font-weight: 700; color: #ff6b6b;">
                                                    ₦{{ number_format($portfolio->price - ($portfolio->price * $portfolio->festive_discount_percentage / 100), 0) }}
                                                </span>
                                            @else
                                                <span style="font-size: 1.3rem; font-weight: 700; color: #667eea;">₦{{ number_format($portfolio->price, 0) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">
                                                <i class="fas fa-eye" style="color: #667eea;"></i> {{ $portfolio->view_count }}
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Footer -->
                                <div class="card-footer" style="background: #f8f9fa; border-top: 1px solid #e9ecef; padding: 15px; border-radius: 0;">
                                    <a href="{{ route('shop.show', $portfolio) }}" class="btn btn-sm w-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; font-weight: 600; padding: 10px;">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $portfolios->links() }}
                </div>
            @else
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">No Portfolios Found</h5>
                        <p class="text-muted">Try adjusting your filters or search term.</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-primary btn-sm">Clear Filters</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function filterCategory(category) {
    try {
        const sortEl = document.getElementById('sort-select');
        const searchEl = document.getElementById('search-input');
        
        const sort = sortEl ? sortEl.value : '';
        const search = searchEl ? searchEl.value : '';
        
        const params = new URLSearchParams();
        if(category) params.append('category', category);
        if(sort && sort !== 'latest') params.append('sort', sort);
        if(search) params.append('search', search);
        
        console.log('✓ Filtering by category:', category);
        window.location.href = '{{ route("shop.index") }}' + (params.toString() ? '?' + params.toString() : '');
    } catch(error) {
        console.error('❌ Filter error:', error);
        alert('Error applying filter. Please try again.');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Shop index page loaded - initializing filters');
    
    // Apply filters button
    const applyBtn = document.getElementById('apply-filters');
    if(applyBtn) {
        applyBtn.addEventListener('click', function(e) {
            e.preventDefault();
            try {
                const categoryRadio = document.querySelector('input[name="category"]:checked');
                const sortSelect = document.getElementById('sort-select');
                const searchInput = document.getElementById('search-input');
                
                const category = categoryRadio ? categoryRadio.value : '';
                const sort = sortSelect ? sortSelect.value : 'latest';
                const search = searchInput ? searchInput.value : '';

                const params = new URLSearchParams();
                if(category) params.append('category', category);
                if(sort && sort !== 'latest') params.append('sort', sort);
                if(search) params.append('search', search);
                
                console.log('✓ Apply filters clicked - category:', category, 'sort:', sort, 'search:', search);
                window.location.href = '{{ route("shop.index") }}' + (params.toString() ? '?' + params.toString() : '');
            } catch(error) {
                console.error('❌ Apply filters error:', error);
                alert('Error applying filters. Please try again.');
            }
        });
    } else {
        console.warn('⚠️ Apply filters button not found');
    }

    // Search button
    const searchBtn = document.getElementById('search-btn');
    if(searchBtn) {
        searchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('✓ Search button clicked');
            if(applyBtn) applyBtn.click();
        });
    }

    // Enter key in search
    const searchInput = document.getElementById('search-input');
    if(searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if(e.key === 'Enter') {
                e.preventDefault();
                console.log('✓ Enter key pressed in search');
                if(applyBtn) applyBtn.click();
            }
        });
    }

    // Card hover effects
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
            this.style.boxShadow = '0 15px 35px rgba(0,0,0,0.15)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 2px 8px rgba(0,0,0,0.08)';
        });
    });

    // Image zoom on hover
    document.querySelectorAll('.card img').forEach(img => {
        img.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.08)';
        });
        img.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    console.log('✓ All event listeners initialized');
});
</script>
@endpush
@endsection
