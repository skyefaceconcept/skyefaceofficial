@extends('layouts.buzbox')

@section('content')

@include('partials.navbar')

<div class="page-header-area" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 50px 0; color: white; position: relative; overflow: hidden;">
  <div style="position: absolute; top: 0; right: 0; width: 300px; height: 300px; background: rgba(255,255,255,0.1); border-radius: 50%; transform: translate(100px, -100px);"></div>
  <div style="position: absolute; bottom: 0; left: 0; width: 250px; height: 250px; background: rgba(255,255,255,0.1); border-radius: 50%; transform: translate(-50px, 100px);"></div>
  
  <div class="container" style="position: relative; z-index: 1;">
    <h1 class="page-title" style="margin: 0; color: white; font-size: 2.5rem; font-weight: 700; letter-spacing: -1px;">Shopping Cart</h1>
    <p class="page-subtitle" style="margin: 10px 0 0 0; color: rgba(255,255,255,0.9); font-size: 1.1rem;">Review and manage your items</p>
  </div>
</div>

<section id="cart" class="cart py-5">
  <div class="container">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="row">
      <div class="col-lg-8">
        <div id="cart-items-container">
          <div class="alert alert-info text-center py-5">
            <i class="fas fa-shopping-cart" style="font-size: 48px; color: #667eea; margin-bottom: 20px; display: block;"></i>
            <h4>Your Cart is Empty</h4>
            <p class="mb-3">You haven't added any items to your cart yet.</p>
            <a href="{{ route('shop.index') }}" class="btn btn-primary">
              <i class="fas fa-shopping-bag"></i> Continue Shopping
            </a>
          </div>
        </div>
      </div>

      <!-- Cart Summary -->
      <div class="col-lg-4">
        <div class="card shadow-sm" style="position: sticky; top: 20px; border: 1px solid #e9ecef; border-radius: 12px;">
          <div class="card-body" style="padding: 30px;">
            <h5 class="card-title mb-4" style="font-weight: 600; color: #333;">Order Summary</h5>

            <div class="d-flex justify-content-between mb-3" style="padding-bottom: 12px; border-bottom: 1px solid #e9ecef;">
              <span style="color: #666;">Subtotal:</span>
              <span class="fw-bold" id="summary-subtotal" style="color: #333;">₦0.00</span>
            </div>

            <div class="d-flex justify-content-between mb-3" style="padding-bottom: 12px; border-bottom: 1px solid #e9ecef;">
              <span style="color: #666;">Shipping:</span>
              <span class="fw-bold" style="color: #28a745; font-weight: 600;">Free</span>
            </div>

            <div class="d-flex justify-content-between mb-4" style="padding-bottom: 12px; border-bottom: 1px solid #e9ecef;">
              <span style="color: #666;">Tax:</span>
              <span class="fw-bold" style="color: #333;">₦0.00</span>
            </div>

            <div class="d-flex justify-content-between mb-4" style="padding: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; color: white;">
              <span class="h6 mb-0" style="font-weight: 600;">Total:</span>
              <span class="h6 mb-0 fw-bold" id="summary-total" style="font-weight: 600;">₦0.00</span>
            </div>

            <button id="checkout-btn" class="btn w-100 mb-2" disabled onclick="proceedToCheckout()" 
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-weight: 600; padding: 12px; transition: all 0.3s;">
              <i class="fas fa-lock"></i> Proceed to Checkout
            </button>

            <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary w-100" style="border: 2px solid #667eea; color: #667eea; border-radius: 8px; font-weight: 600; padding: 10px;">
              <i class="fas fa-arrow-left"></i> Continue Shopping
            </a>
          </div>
        </div>

        <!-- Trust Badges -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; margin-top: 20px; text-align: center;">
          <div style="margin-bottom: 15px;">
            <i class="fas fa-lock" style="color: #667eea; font-size: 1.5rem; display: block; margin-bottom: 8px;"></i>
            <small style="color: #666;">Secure Checkout</small>
          </div>
          <div style="margin-bottom: 15px;">
            <i class="fas fa-shipping-fast" style="color: #667eea; font-size: 1.5rem; display: block; margin-bottom: 8px;"></i>
            <small style="color: #666;">Fast Digital Delivery</small>
          </div>
          <div>
            <i class="fas fa-undo" style="color: #667eea; font-size: 1.5rem; display: block; margin-bottom: 8px;"></i>
            <small style="color: #666;">30-Day Money-Back Guarantee</small>
          </div>
        </div>

        <!-- Promo Code -->
        <div class="card mt-3" style="border: 1px solid #e9ecef; border-radius: 12px;">
          <div class="card-body" style="padding: 20px;">
            <h6 class="card-title mb-3" style="font-weight: 600; color: #333;">
              <i class="fas fa-tag" style="color: #667eea;"></i> Have a Promo Code?
            </h6>
            <form action="#" method="POST">
              @csrf
              <div class="input-group" style="border-radius: 8px; overflow: hidden;">
                <input type="text" class="form-control" placeholder="Enter promo code" name="promo_code" style="border: 1px solid #ddd; border-radius: 6px 0 0 6px;">
                <button class="btn" type="submit" style="background: #667eea; color: white; border: none; border-radius: 0 6px 6px 0; font-weight: 600;">Apply</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
.page-header-area {
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.page-title {
  font-size: 2.5rem;
  font-weight: 700;
}

.page-subtitle {
  font-size: 1.1rem;
}

@media (max-width: 768px) {
  .page-title {
    font-size: 2rem;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Cart page loaded');
    loadCart();
});

function loadCart() {
    let cart = [];
    try {
        const cartData = localStorage.getItem('shop_cart');
        cart = cartData ? JSON.parse(cartData) : [];
    } catch(e) {
        console.warn('Failed to parse cart data:', e);
        localStorage.removeItem('shop_cart');
        cart = [];
    }
    console.log('Loading cart with items:', cart);

    const container = document.getElementById('cart-items-container');

    if (cart.length === 0) {
        // Empty cart
        document.getElementById('summary-subtotal').textContent = '₦0.00';
        document.getElementById('summary-total').textContent = '₦0.00';
        document.getElementById('checkout-btn').disabled = true;
        return;
    }

    // Build cart items HTML
    let itemsHTML = '<div class="card">';
    let subtotal = 0;

    cart.forEach((item, index) => {
        subtotal += item.totalPrice * (item.quantity || 1);

        const itemTotal = item.totalPrice * (item.quantity || 1);
        const licenseLabel = {
            '6months': '6 Months',
            '1year': '1 Year',
            '2years': '2 Years'
        }[item.licenseDuration] || item.licenseDuration;

        itemsHTML += `
            <div class="card-body border-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="mb-2">${item.name}</h6>
                        <small class="text-muted d-block">License: ${licenseLabel}</small>
                        <small class="text-muted d-block">Base Price: ₦${item.basePrice.toLocaleString('en-NG')}</small>
                        <small class="text-muted d-block">License Price: ₦${item.licensePrice.toLocaleString('en-NG')}</small>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group input-group-sm">
                            <button class="btn btn-outline-secondary" onclick="decreaseQuantity(${index})">−</button>
                            <input type="text" class="form-control text-center" value="${item.quantity || 1}" readonly style="width: 50px;">
                            <button class="btn btn-outline-secondary" onclick="increaseQuantity(${index})">+</button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="text-end">
                            <h6 class="mb-0">₦${itemTotal.toLocaleString('en-NG')}</h6>
                            <small class="text-danger" style="cursor: pointer;" onclick="removeFromCart(${index})">
                                <i class="fas fa-trash"></i> Remove
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    itemsHTML += '</div>';

    // Update container
    container.innerHTML = itemsHTML;

    // Update order summary with correct IDs
    if (document.getElementById('summary-subtotal')) {
        document.getElementById('summary-subtotal').textContent = '₦' + subtotal.toLocaleString('en-NG');
    }
    if (document.getElementById('summary-total')) {
        document.getElementById('summary-total').textContent = '₦' + subtotal.toLocaleString('en-NG');
    }
    
    const checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.disabled = false;
        checkoutBtn.style.opacity = '1';
        checkoutBtn.style.cursor = 'pointer';
    }

    console.log('✓ Cart updated with subtotal: ₦' + subtotal.toLocaleString('en-NG'));
}

function increaseQuantity(index) {
    let cart = [];
    try {
        const cartData = localStorage.getItem('shop_cart');
        cart = cartData ? JSON.parse(cartData) : [];
    } catch(e) {
        console.warn('Failed to parse cart data:', e);
        return;
    }
    if (cart[index]) {
        cart[index].quantity = (cart[index].quantity || 1) + 1;
        localStorage.setItem('shop_cart', JSON.stringify(cart));
        loadCart();
        console.log('✓ Quantity increased');
    }
}

function decreaseQuantity(index) {
    let cart = [];
    try {
        const cartData = localStorage.getItem('shop_cart');
        cart = cartData ? JSON.parse(cartData) : [];
    } catch(e) {
        console.warn('Failed to parse cart data:', e);
        return;
    }
    if (cart[index]) {
        cart[index].quantity = Math.max(1, (cart[index].quantity || 1) - 1);
        localStorage.setItem('shop_cart', JSON.stringify(cart));
        loadCart();
        console.log('✓ Quantity decreased');
    }
}

function removeFromCart(index) {
    if (confirm('Are you sure you want to remove this item?')) {
        let cart = [];
        try {
            const cartData = localStorage.getItem('shop_cart');
            cart = cartData ? JSON.parse(cartData) : [];
        } catch(e) {
            console.warn('Failed to parse cart data:', e);
            localStorage.removeItem('shop_cart');
            location.reload();
            return;
        }
        cart.splice(index, 1);
        localStorage.setItem('shop_cart', JSON.stringify(cart));
        loadCart();
        console.log('✓ Item removed from cart');

        if (cart.length === 0) {
            location.reload();
        }
    }
}

function clearCart() {
    if (confirm('Clear all items from cart?')) {
        localStorage.removeItem('shop_cart');
        location.reload();
    }
}

function proceedToCheckout() {
    try {
        console.log('=== CHECKOUT BUTTON CLICKED ===');
        let cart = [];
        try {
            const cartData = localStorage.getItem('shop_cart');
            cart = cartData ? JSON.parse(cartData) : [];
        } catch(e) {
            console.error('Failed to parse cart data:', e);
            alert('❌ Error loading cart. Please refresh and try again.');
            return false;
        }
        console.log('Cart items:', cart.length);

        if (cart.length === 0) {
            alert('❌ Your cart is empty. Please add items before proceeding to checkout.');
            console.warn('❌ Cart is empty');
            return false;
        }

        // Validate all cart items have required fields
        for (let item of cart) {
            if (!item.id || !item.name || !item.licenseDuration || !item.totalPrice) {
                alert('❌ Cart data is invalid. Please add items to your cart again.');
                console.error('❌ Invalid cart item:', item);
                localStorage.removeItem('shop_cart');
                location.reload();
                return false;
            }
        }

        console.log('✓ Proceeding to checkout with', cart.length, 'item(s)');

        // Get the total amount
        let total = 0;
        cart.forEach(item => {
            const itemPrice = parseFloat(item.totalPrice) || 0;
            const qty = parseInt(item.quantity) || 1;
            total += (itemPrice * qty);
        });

        if (total <= 0) {
            alert('❌ Invalid total amount. Please check your cart.');
            console.error('❌ Invalid total:', total);
            return false;
        }

        console.log('✓ Cart total: ₦' + total.toLocaleString('en-NG'));

        // Create a hidden form and submit it to checkout
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("checkout.show") }}';
        console.log('✓ Form action:', form.action);

        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = csrfToken.getAttribute('content');
            form.appendChild(tokenInput);
            console.log('✓ CSRF token added');
        } else {
            console.error('❌ CSRF token not found!');
            alert('❌ Security error. Please refresh and try again.');
            return false;
        }

        // Add cart data as JSON
        const cartInput = document.createElement('input');
        cartInput.type = 'hidden';
        cartInput.name = 'cart';
        cartInput.value = JSON.stringify(cart);
        form.appendChild(cartInput);
        console.log('✓ Cart data added');

        // Add total
        const totalInput = document.createElement('input');
        totalInput.type = 'hidden';
        totalInput.name = 'total';
        totalInput.value = total.toFixed(2);
        form.appendChild(totalInput);
        console.log('✓ Total added:', total.toFixed(2));

        document.body.appendChild(form);
        console.log('✓ Form appended to body');
        console.log('✓ Submitting checkout form...');
        form.submit();
        return true;
    } catch(error) {
        console.error('❌ Checkout error:', error);
        alert('❌ Error proceeding to checkout: ' + error.message);
        return false;
    }
}
</script>
@endsection
