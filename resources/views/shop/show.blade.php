@extends('layouts.buzbox')

@section('content')
@include('partials.navbar')



<div class="container py-5">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('shop.index') }}" class="text-decoration-none">
            <i class="fas fa-arrow-left"></i> Back to Shop
        </a>
    </div>

    <!-- Main Portfolio Section -->
    <div class="row mb-5">
        <div class="col-lg-7">
            <!-- Gallery -->
            <div class="mb-4">
                <div id="portfolio-gallery">
                    @if($footages->where('type', 'photo')->count() > 0)
                        <div id="main-image" style="height: 500px; background: #f0f0f0; border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                            <img id="gallery-main" src="{{ asset('storage/' . $footages->where('type', 'photo')->first()->media_path) }}"
                                 alt="Gallery" style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                    @elseif($footages->where('type', 'video')->count() > 0)
                        <video id="gallery-video" width="100%" height="500" style="background: #000; border-radius: 8px; display: block;">
                            <source src="{{ asset('storage/' . $footages->where('type', 'video')->first()->media_path) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @else
                        <div style="height: 500px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <span class="text-muted"><i class="fas fa-image" style="font-size: 3rem;"></i></span>
                        </div>
                    @endif
                </div>

                <!-- Thumbnail Strip -->
                @if($footages->count() > 1)
                    <div class="mt-3 d-flex gap-2" style="overflow-x: auto; padding-bottom: 10px;">
                        @foreach($footages as $footage)
                            <div class="flex-shrink-0" style="cursor: pointer;">
                                @if($footage->isPhoto())
                                    <img src="{{ asset('storage/' . $footage->media_path) }}"
                                         alt="{{ $footage->title }}"
                                         onclick="switchGallery({{ $loop->index }})"
                                         class="thumbnail {{ $loop->first ? 'active' : '' }}"
                                         style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; border: 2px solid {{ $loop->first ? '#007bff' : '#ddd' }}; transition: border-color 0.3s;">
                                @else
                                    <div onclick="switchGallery({{ $loop->index }})"
                                         class="thumbnail {{ $loop->first ? 'active' : '' }}"
                                         style="width: 80px; height: 80px; background: #000; border-radius: 4px; border: 2px solid {{ $loop->first ? '#007bff' : '#ddd' }}; display: flex; align-items: center; justify-content: center; transition: border-color 0.3s;">
                                        <i class="fas fa-video text-white"></i>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Details Section -->
            @if($portfolio->detailed_description)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">About This Portfolio</h5>
                    </div>
                    <div class="card-body">
                        {{ $portfolio->detailed_description }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-5">
            <div class="card sticky-top" style="top: 20px; border: 1px solid #e9ecef; box-shadow: 0 2px 20px rgba(0,0,0,0.08); border-radius: 12px;">
                <div class="card-body" style="padding: 30px;">
                    <!-- Title & Rating -->
                    <h2 class="h4 mb-3" style="font-weight: 700; color: #333;">{{ $portfolio->title }}</h2>
                    
                    <div class="mb-4" style="padding-bottom: 20px; border-bottom: 1px solid #e9ecef;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                            <span style="color: #ffc107; font-size: 0.9rem;">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </span>
                            <strong style="color: #667eea;">4.5</strong>
                            <small class="text-muted">(127 reviews)</small>
                        </div>
                        
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            @if($portfolio->category === 'web')
                                <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 6px 12px; border-radius: 6px;">
                                    <i class="fas fa-globe"></i> Web Application
                                </span>
                            @elseif($portfolio->category === 'mobile')
                                <span class="badge" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 6px 12px; border-radius: 6px;">
                                    <i class="fas fa-mobile-alt"></i> Mobile App
                                </span>
                            @else
                                <span class="badge" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #333; padding: 6px 12px; border-radius: 6px;">
                                    <i class="fas fa-palette"></i> Design Package
                                </span>
                            @endif
                            <span class="badge" style="background: #f0f0f0; color: #666; padding: 6px 12px; border-radius: 6px;">
                                <i class="fas fa-eye"></i> {{ $portfolio->view_count }} views
                            </span>
                        </div>
                    </div>

                    <p class="text-muted small mb-4" style="line-height: 1.6;">{{ $portfolio->description }}</p>

                    <!-- Festive Discount Badge -->
                    @if($portfolio->festive_discount_enabled && $portfolio->festive_discount_percentage > 0)
                        <div style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%); padding: 15px 20px; border-radius: 8px; color: white; margin-bottom: 20px; display: flex; align-items: center; gap: 15px;">
                            <div style="font-size: 2rem; font-weight: 700;">{{ (int)$portfolio->festive_discount_percentage }}%</div>
                            <div>
                                <div style="font-weight: 700; font-size: 1.1rem;">Special Festive Discount!</div>
                                <small style="opacity: 0.9;">Limited time offer - Save {{ (int)$portfolio->festive_discount_percentage }}% on this portfolio</small>
                            </div>
                        </div>
                    @endif
                    
                    <!-- License Pricing Options (Web Category Only) -->
                    @if(strtolower($portfolio->category) === 'web')
                        <div class="mb-4">
                            <h6 class="mb-3" style="font-weight: 600; color: #333;">Choose License Duration</h6>

                            <!-- License Duration Options -->
                            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #e9ecef;">
                                <div class="btn-group w-100" role="group" style="display: flex; gap: 8px;">
                                    @if($portfolio->price_6months)
                                        <input type="radio" class="btn-check" name="license_duration" id="duration_6m" value="6months">
                                        <label class="btn btn-outline-secondary" for="duration_6m" style="flex: 1; border-radius: 6px; cursor: pointer; border: 2px solid #ddd; padding: 12px 10px; text-align: center;">
                                            <small style="display: block; color: #666;">6 Months</small>
                                            <strong id="price_6m_label" style="display: block; color: #667eea;">₦{{ number_format($portfolio->price_6months, 0) }}</strong>
                                        </label>
                                    @endif
                                    @if($portfolio->price_1year)
                                        <input type="radio" class="btn-check" name="license_duration" id="duration_1y" value="1year" checked>
                                        <label class="btn btn-outline-secondary" for="duration_1y" style="flex: 1; border-radius: 6px; cursor: pointer; border: 2px solid #667eea; padding: 12px 10px; text-align: center; background: rgba(102, 126, 234, 0.05);">
                                            <small style="display: block; color: #667eea;">1 Year</small>
                                            <strong id="price_1y_label" style="display: block; color: #667eea;">₦{{ number_format($portfolio->price_1year, 0) }}</strong>
                                        </label>
                                    @endif
                                    @if($portfolio->price_2years)
                                        <input type="radio" class="btn-check" name="license_duration" id="duration_2y" value="2years">
                                        <label class="btn btn-outline-secondary" for="duration_2y" style="flex: 1; border-radius: 6px; cursor: pointer; border: 2px solid #ddd; padding: 12px 10px; text-align: center;">
                                            <small style="display: block; color: #666;">2 Years</small>
                                            <strong id="price_2y_label" style="display: block; color: #667eea;">₦{{ number_format($portfolio->price_2years, 0) }}</strong>
                                        </label>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Price Display -->
                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 25px; border-radius: 8px; color: white; margin-bottom: 20px;">
                        <small style="opacity: 0.9; display: block; margin-bottom: 5px;">
                            <i class="fas fa-tag"></i> Total Package Price
                        </small>
                        @if($portfolio->festive_discount_enabled && $portfolio->festive_discount_percentage > 0)
                            <div style="margin-bottom: 10px;">
                                <small style="opacity: 0.8; text-decoration: line-through; display: block;">
                                    ₦{{ number_format(($portfolio->price + ($portfolio->price_1year ?? 0)), 0) }}
                                </small>
                            </div>
                        @endif
                        <h2 class="mb-0" id="total-price" style="font-size: 2rem; font-weight: 700;">
                            @if($portfolio->festive_discount_enabled && $portfolio->festive_discount_percentage > 0)
                                ₦{{ number_format((($portfolio->price + ($portfolio->price_1year ?? 0)) * (100 - $portfolio->festive_discount_percentage)) / 100, 0) }}
                            @else
                                ₦{{ number_format(($portfolio->price + ($portfolio->price_1year ?? 0)), 0) }}
                            @endif
                        </h2>
                    </div>

                    <!-- Add to Cart -->
                    <button type="button" class="btn w-100 mb-3" onclick="addToCart({{ $portfolio->id }})" 
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-weight: 600; padding: 14px; font-size: 1rem; transition: all 0.3s;">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>

                    <!-- Buy Now -->
                    <form action="{{ route('checkout.show') }}" method="POST" id="buy-now-form" style="display: block; width: 100%; margin-bottom: 20px;" onsubmit="return validateBuyNow(event)">
                        @csrf
                        <input type="hidden" name="portfolio_id" value="{{ $portfolio->id }}">
                        <input type="hidden" name="license_duration" id="license_duration_input" value="1year">
                        <button type="submit" class="btn btn-outline-secondary w-100" style="border: 2px solid #667eea; color: #667eea; border-radius: 8px; font-weight: 600; padding: 12px; transition: all 0.3s; cursor: pointer;">
                            <i class="fas fa-bolt"></i> Buy Now
                        </button>
                    </form>

                    <!-- Trust Badges -->
                    <div style="padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-around; text-align: center; font-size: 0.85rem;">
                            <div>
                                <i class="fas fa-lock" style="color: #667eea; font-size: 1.3rem; margin-bottom: 5px; display: block;"></i>
                                <small style="color: #666;">Secure Payment</small>
                            </div>
                            <div>
                                <i class="fas fa-undo" style="color: #667eea; font-size: 1.3rem; margin-bottom: 5px; display: block;"></i>
                                <small style="color: #666;">30-Day Refund</small>
                            </div>
                            <div>
                                <i class="fas fa-headset" style="color: #667eea; font-size: 1.3rem; margin-bottom: 5px; display: block;"></i>
                                <small style="color: #666;">24/7 Support</small>
                            </div>
                        </div>
                    </div>

                    <!-- What You Get -->
                    <div style="border-top: 1px solid #e9ecef; padding-top: 20px;">
                        <h6 class="mb-3" style="font-weight: 600; color: #333;">
                            <i class="fas fa-box" style="color: #667eea;"></i> What You Get
                        </h6>
                        <ul class="list-unstyled small" style="font-size: 0.95rem;">
                            <li class="mb-2">
                                <i class="fas fa-check-circle" style="color: #28a745; margin-right: 8px;"></i>
                                <span style="color: #333;">Full Source Code</span>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle" style="color: #28a745; margin-right: 8px;"></i>
                                <span style="color: #333;">Lifetime License</span>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle" style="color: #28a745; margin-right: 8px;"></i>
                                <span style="color: #333;">Email Support</span>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle" style="color: #28a745; margin-right: 8px;"></i>
                                <span style="color: #333;">Complete Documentation</span>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle" style="color: #28a745; margin-right: 8px;"></i>
                                <span style="color: #333;">Regular Updates</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Portfolios -->
    @if($relatedPortfolios->count() > 0)
        <div class="mt-5 pt-5 border-top">
            <h3 class="mb-4">More in {{ ucfirst($portfolio->category) }}</h3>
            <div class="row">
                @foreach($relatedPortfolios as $related)
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div style="height: 150px; background: #f0f0f0; overflow: hidden;">
                                @if($related->thumbnail)
                                    <a href="{{ route('shop.show', $related) }}" class="text-decoration-none">
                                        <img src="{{ asset('storage/' . $related->thumbnail) }}" alt="{{ $related->title }}"
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    </a>
                                @endif
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="{{ route('shop.show', $related) }}" class="text-decoration-none text-dark">
                                        {{ Str::limit($related->title, 30) }}
                                    </a>
                                </h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-primary">₦{{ number_format($related->price, 2) }}</span>
                                    <a href="{{ route('shop.show', $related) }}" class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@endsection

<script>
// License pricing initialization
document.addEventListener('DOMContentLoaded', function() {
    console.log('✓ DOMContentLoaded event fired');

    // Store prices
    const basePrice = {{ $portfolio->price }};
    const portfolioPrices = {
        '6months': {{ $portfolio->price_6months ?? 0 }},
        '1year': {{ $portfolio->price_1year ?? 0 }},
        '2years': {{ $portfolio->price_2years ?? 0 }},
    };

    console.log('✓ Portfolio category:', '{{ strtolower($portfolio->category) }}');
    console.log('✓ Base price:', basePrice);
    console.log('✓ Portfolio prices:', portfolioPrices);

    // Function to update total price
    function updateTotalPrice() {
        console.log('=== updateTotalPrice called ===');

        let licensePrice = 0;
        const selected = document.querySelector('input[name="license_duration"]:checked');

        if (selected) {
            const duration = selected.value;
            licensePrice = portfolioPrices[duration] || 0;
            console.log('Selected duration:', duration, '→ License price:', licensePrice);
        }

        const totalPrice = basePrice + licensePrice;

        // Update price elements
        const licensePriceEl = document.getElementById('license-price');
        const totalPriceEl = document.getElementById('total-price');

        if (licensePriceEl) {
            const formattedLicense = licensePrice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            licensePriceEl.textContent = '₦' + formattedLicense;
            console.log('✓ Updated license price to: ₦' + formattedLicense);
        }

        if (totalPriceEl) {
            const formattedTotal = totalPrice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            totalPriceEl.textContent = '₦' + formattedTotal;
            console.log('✓ Updated total price to: ₦' + formattedTotal);
        }

        // Update hidden input
        const inputEl = document.getElementById('license_duration_input');
        if (inputEl && selected) {
            inputEl.value = selected.value;
            console.log('✓ Updated license_duration_input to:', selected.value);
        } else if (inputEl) {
            inputEl.value = '1year'; // Default to 1 year
            console.log('✓ Set default license_duration_input to: 1year');
        }
    }

    // Initial update
    updateTotalPrice();

    // Add event listeners to radio buttons
    const radioButtons = document.querySelectorAll('input[name="license_duration"]');
    console.log('✓ Found', radioButtons.length, 'radio buttons');

    radioButtons.forEach((radio) => {
        radio.addEventListener('change', function() {
            console.log('🔘 Radio button clicked:', this.value);
            updateTotalPrice();
        });
    });

    console.log('✓ Initialization complete - ready to select licenses');
});

function switchGallery(index) {
    try {
        console.log('=== SWITCH GALLERY CALLED ===');
        console.log('Switching to image index:', index);

        const footages = {!! json_encode($footages->map(fn($f) => ['type' => $f->type, 'path' => asset('storage/' . $f->media_path)])) !!};
        console.log('Total footages available:', footages.length);

        if (!footages || footages.length === 0) {
            console.error('❌ No footages found');
            return false;
        }

        if (index < 0 || index >= footages.length) {
            console.error('❌ Invalid footage index:', index);
            return false;
        }

        const footage = footages[index];
        console.log('Footage type:', footage.type);

        // Check if main-image container exists
        const mainImageEl = document.getElementById('main-image');
        if (!mainImageEl) {
            console.error('❌ Main image container not found');
            return false;
        }

        if (footage.type === 'photo') {
            mainImageEl.innerHTML = `<img id="gallery-main" src="${footage.path}" alt="Gallery" style="width: 100%; height: 100%; object-fit: contain;">`;
            console.log('✓ Switched to photo');
            
            // Remove video if exists
            const videoEl = document.getElementById('gallery-video');
            if(videoEl) {
                videoEl.remove();
                console.log('✓ Removed existing video');
            }
        } else {
            mainImageEl.innerHTML = `<video id="gallery-video" width="100%" height="500" style="background: #000; border-radius: 8px; display: block;" controls><source src="${footage.path}" type="video/mp4">Your browser does not support the video tag.</video>`;
            console.log('✓ Switched to video');
        }

        // Update active thumbnail styling
        const thumbnails = document.querySelectorAll('.thumbnail');
        if (thumbnails && thumbnails.length > 0) {
            thumbnails.forEach((thumb, i) => {
                if (i === index) {
                    thumb.style.borderColor = '#007bff';
                    thumb.style.borderWidth = '3px';
                    console.log('✓ Highlighted thumbnail', i);
                } else {
                    thumb.style.borderColor = '#ddd';
                    thumb.style.borderWidth = '1px';
                }
            });
        }

        console.log('✓ Gallery switch completed successfully');
        return true;
    } catch(error) {
        console.error('❌ Gallery switch error:', error);
        return false;
    }
}

function addToCart(portfolioId) {
    try {
        console.log('=== ADD TO CART BUTTON CLICKED ===');
        console.log('Portfolio ID:', portfolioId);
        
        // Get selected license duration
        const licenseSelect = document.querySelector('input[name="license_duration"]:checked');
        const licenseDuration = licenseSelect ? licenseSelect.value : '';
        console.log('Selected license duration:', licenseDuration);

        if (!licenseDuration) {
            alert('❌ Please select a license duration');
            console.warn('❌ No license duration selected');
            return;
        }

        // Get price data
        const nameEl = document.querySelector('h2.h4');
        const portfolioName = nameEl ? nameEl.textContent.trim() : 'Unknown Product';
        const basePrice = {{ $portfolio->price }};
        const portfolioPrices = {
            '6months': {{ $portfolio->price_6months ?? 0 }},
            '1year': {{ $portfolio->price_1year ?? 0 }},
            '2years': {{ $portfolio->price_2years ?? 0 }},
        };

        const licensePrice = portfolioPrices[licenseDuration] || 0;
        const totalPrice = basePrice + licensePrice;
        console.log('Base price:', basePrice, 'License price:', licensePrice, 'Total:', totalPrice);

        // Create cart item
        const cartItem = {
            id: portfolioId,
            name: portfolioName,
            basePrice: basePrice,
            licenseDuration: licenseDuration,
            licensePrice: licensePrice,
            totalPrice: totalPrice,
            timestamp: new Date().getTime()
        };
        console.log('Cart item created:', cartItem);

        // Get existing cart from localStorage with error handling
        let cart = [];
        try {
            const cartData = localStorage.getItem('shop_cart');
            cart = cartData ? JSON.parse(cartData) : [];
        } catch(e) {
            console.warn('Failed to parse cart data:', e);
            localStorage.removeItem('shop_cart');
            cart = [];
        }
        console.log('Current cart items:', cart.length);

        // Check if item already exists
        const existingIndex = cart.findIndex(item => item.id === portfolioId && item.licenseDuration === licenseDuration);

        if (existingIndex >= 0) {
            // Update quantity if exists
            cart[existingIndex].quantity = (cart[existingIndex].quantity || 1) + 1;
            console.log('✓ Updated cart item quantity to:', cart[existingIndex].quantity);
        } else {
            // Add new item
            cartItem.quantity = 1;
            cart.push(cartItem);
            console.log('✓ Added new item to cart');
        }

        // Save cart to localStorage
        localStorage.setItem('shop_cart', JSON.stringify(cart));
        console.log('✓ Cart saved to localStorage');

        // Update cart count in navbar (if it exists)
        updateCartCount();

        // Show success message
        const message = document.createElement('div');
        message.className = 'alert alert-success alert-dismissible fade show';
        message.style.cssText = 'position: fixed; top: 80px; right: 20px; z-index: 1000; width: 300px;';
        message.innerHTML = `
            <strong>✓ Added to Cart!</strong> ${portfolioName} - ₦${totalPrice.toLocaleString('en-NG')}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(message);
        console.log('✓ Success message shown');

        // Auto-remove message after 3 seconds
        setTimeout(() => {
            message.remove();
        }, 3000);

        console.log('Total cart count:', cart.reduce((sum, item) => sum + (item.quantity || 1), 0));
        return true;
    } catch(error) {
        console.error('❌ Add to cart error:', error);
        alert('❌ Error adding to cart: ' + error.message);
        return false;
    }
}

function updateCartCount() {
    let cart = [];
    try {
        const cartData = localStorage.getItem('shop_cart');
        cart = cartData ? JSON.parse(cartData) : [];
    } catch(e) {
        console.warn('Failed to parse cart data:', e);
        cart = [];
    }
    const totalItems = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);

    // Try to update cart count in navbar
    const cartCountEl = document.getElementById('cart-count');
    if (cartCountEl) {
        cartCountEl.textContent = totalItems;
        cartCountEl.style.display = totalItems > 0 ? 'inline' : 'none';
    }
    
    console.log('✓ Updated cart count:', totalItems);
}

function validateBuyNow(event) {
    try {
        console.log('=== BUY NOW BUTTON CLICKED ===');
        event.preventDefault();
        
        // Get license duration input element
        const licenseInput = document.getElementById('license_duration_input');
        if (!licenseInput) {
            console.error('❌ License duration input element not found');
            alert('❌ Error: Form element not found. Please refresh and try again.');
            return false;
        }

        const licenseDuration = licenseInput.value;
        console.log('Selected license duration:', licenseDuration);
        
        if (!licenseDuration || licenseDuration === '') {
            console.warn('❌ No license duration selected');
            alert('❌ Please select a license duration before proceeding to checkout.');
            return false;
        }
        
        console.log('✓ Buy Now validation passed');
        console.log('✓ Submitting form...');
        
        // Submit the form
        const form = event.target;
        if (!form) {
            console.error('❌ Form element not found');
            alert('❌ Error: Form submission failed. Please try again.');
            return false;
        }

        form.submit();
        console.log('✓ Form submitted successfully');
        return true;
    } catch(error) {
        console.error('❌ Buy Now validation error:', error);
        alert('❌ Error validating checkout: ' + error.message);
        return false;
    }
}
</script>
