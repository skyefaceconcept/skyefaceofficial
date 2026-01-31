@extends('layouts.buzbox')

@section('content')

@include('partials.navbar')

<!-- Hero Section -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 50px 0; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: 0; right: 0; width: 300px; height: 300px; background: rgba(255,255,255,0.1); border-radius: 50%; transform: translate(100px, -100px);"></div>
    <div style="position: absolute; bottom: 0; left: 0; width: 250px; height: 250px; background: rgba(255,255,255,0.1); border-radius: 50%; transform: translate(-50px, 100px);"></div>
    
    <div class="container" style="position: relative; z-index: 1;">
        <h1 style="margin: 0; color: white; font-size: 2.5rem; font-weight: 700; letter-spacing: -1px;">Secure Checkout</h1>
        <p style="margin: 10px 0 0 0; color: rgba(255,255,255,0.9); font-size: 1rem;">Complete your purchase in just 3 simple steps</p>
    </div>
</div>

<!-- Progress Steps -->
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-12">
            <div style="display: flex; justify-content: space-between; position: relative; margin-bottom: 50px;">
                <!-- Progress Bar -->
                <div style="position: absolute; top: 20px; left: 0; right: 0; height: 3px; background: #e9ecef; z-index: 0;"></div>
                <div style="position: absolute; top: 20px; left: 0; width: 66%; height: 3px; background: linear-gradient(90deg, #667eea, #764ba2); z-index: 1;"></div>
                
                <!-- Step 1 -->
                <div style="position: relative; z-index: 2; flex: 1; text-align: center;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; margin: 0 auto 15px; font-size: 1.2rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                        1
                    </div>
                    <h6 style="color: #333; font-weight: 600; margin: 0;">Billing Info</h6>
                    <small style="color: #999;">Your details</small>
                </div>
                
                <!-- Step 2 -->
                <div style="position: relative; z-index: 2; flex: 1; text-align: center;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; margin: 0 auto 15px; font-size: 1.2rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                        2
                    </div>
                    <h6 style="color: #333; font-weight: 600; margin: 0;">Payment Method</h6>
                    <small style="color: #999;">Choose payment</small>
                </div>
                
                <!-- Step 3 -->
                <div style="position: relative; z-index: 2; flex: 1; text-align: center;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; margin: 0 auto 15px; font-size: 1.2rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                        3
                    </div>
                    <h6 style="color: #333; font-weight: 600; margin: 0;">Confirm & Pay</h6>
                    <small style="color: #999;">Complete order</small>
                </div>
            </div>
        </div>
    </div>
</div>

<section id="checkout" class="checkout pt-2 pb-5">
  <div class="container">
    <div class="row">
      <!-- Checkout Form -->
      <div class="col-lg-8">
        <!-- Back Button -->
        <div class="mb-4">
          <a href="{{ route('cart.show') }}" class="text-decoration-none" style="color: #667eea; font-weight: 500;">
            <i class="fas fa-arrow-left"></i> Back to Cart
          </a>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form" onsubmit="return validateCheckoutForm()">
          @csrf

          <!-- Pass cart data to the order -->
          @if(isset($cart))
            <input type="hidden" name="cart" value="{{ json_encode($cart) }}">
            <input type="hidden" name="total" value="{{ $total }}">
          @elseif(isset($portfolio))
            <input type="hidden" name="portfolio_id" value="{{ $portfolio->id }}">
            <input type="hidden" name="license_duration" value="{{ $licenseDuration }}">
          @endif

          <!-- Display validation errors if any -->
          @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-left: 4px solid #dc3545; background: #fff5f5; border-radius: 8px; padding: 20px;">
              <strong style="color: #dc3545;">
                <i class="fas fa-exclamation-circle"></i> Please fix the following errors:
              </strong>
              <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                  <li style="color: #666;">{{ $error }}</li>
                @endforeach
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <!-- Billing Information Section -->
          <div style="background: white; padding: 30px; border-radius: 12px; border: 1px solid #e9ecef; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.06);">
            <div style="display: flex; align-items: center; margin-bottom: 25px;">
              <div style="width: 30px; height: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; margin-right: 15px;">
                1
              </div>
              <h4 class="mb-0" style="font-weight: 700; color: #333;">Billing Information</h4>
            </div>

            <!-- Saved Addresses Section -->
            @if(count($savedAddresses) > 0)
            <div style="background: #f0f7ff; padding: 20px; border-radius: 10px; border: 2px solid #cce5ff; margin-bottom: 25px;">
              <h5 style="color: #333; font-weight: 700; margin-bottom: 15px;">
                <i class="fas fa-check-circle" style="color: #28a745; margin-right: 8px;"></i> Your Saved Addresses
              </h5>
              
              @foreach($savedAddresses as $address)
              <div style="background: white; padding: 15px; border-radius: 8px; margin-bottom: 12px; border: 2px solid transparent; cursor: pointer; transition: all 0.3s;"
                   onclick="selectSavedAddress({{ $address->id }}, '{{ $address->customer_name }}', '{{ $address->customer_email }}', '{{ $address->customer_phone }}', '{{ $address->address }}', '{{ $address->city }}', '{{ $address->state }}', '{{ $address->zip }}', '{{ $address->country }}')"
                   id="address-{{ $address->id }}"
                   onmouseover="this.style.borderColor='#667eea'; this.style.backgroundColor='#f8f9fa';"
                   onmouseout="this.style.borderColor='transparent'; this.style.backgroundColor='white';">
                <div style="display: flex; align-items: flex-start;">
                  <input type="radio" name="billing_address_id" value="{{ $address->id }}" style="width: 18px; height: 18px; margin-right: 12px; margin-top: 3px; cursor: pointer;">
                  <div style="flex: 1;">
                    <p style="margin: 0 0 8px 0; font-weight: 600; color: #333;">{{ $address->customer_name }}</p>
                    <p style="margin: 0 0 5px 0; color: #666; font-size: 0.9rem;">{{ $address->address }}, {{ $address->city }}, {{ $address->state }} {{ $address->zip }}</p>
                    <p style="margin: 0; color: #999; font-size: 0.85rem;">
                      <i class="fas fa-envelope" style="margin-right: 5px;"></i>{{ $address->customer_email }}
                      <i class="fas fa-phone" style="margin: 0 5px;"></i>{{ $address->customer_phone }}
                    </p>
                  </div>
                </div>
              </div>
              @endforeach

              <button type="button" class="btn btn-link mt-3" style="color: #667eea; font-weight: 600; padding: 0;" onclick="showNewAddressForm()">
                <i class="fas fa-plus-circle"></i> Use a different address
              </button>
            </div>
            @endif

            <!-- New/Edit Address Form -->
            <div id="address-form" style="@if(count($savedAddresses) > 0) display: none; @endif">
              @if(count($savedAddresses) > 0)
              <div style="background: #fffbea; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107; margin-bottom: 20px;">
                <small style="color: #856404;">
                  <i class="fas fa-info-circle"></i> Fill in the address details below or select from your saved addresses above
                </small>
              </div>
              @endif

              <div class="row">
                <div class="col-md-6 mb-4">
                  <label for="customer_name" class="form-label" style="font-weight: 600; color: #333; margin-bottom: 10px;">
                    Full Name <span class="text-danger">*</span>
                  </label>
                  <input type="text" class="form-control @error('customer_name') is-invalid @enderror"
                         id="customer_name" name="customer_name" value="{{ old('customer_name') }}" 
                         placeholder="John Doe" required style="border-radius: 8px; padding: 12px; border: 1px solid #ddd; font-size: 0.95rem;">
                  @error('customer_name')
                    <div class="invalid-feedback d-block" style="color: #dc3545;">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-4">
                  <label for="customer_email" class="form-label" style="font-weight: 600; color: #333; margin-bottom: 10px;">
                    Email Address <span class="text-danger">*</span>
                  </label>
                  <input type="email" class="form-control @error('customer_email') is-invalid @enderror"
                         id="customer_email" name="customer_email" value="{{ old('customer_email') }}" 
                         placeholder="john@example.com" required style="border-radius: 8px; padding: 12px; border: 1px solid #ddd; font-size: 0.95rem;"
                         onchange="loadSavedAddresses(this.value)">
                  @error('customer_email')
                    <div class="invalid-feedback d-block" style="color: #dc3545;">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-4">
                  <label for="customer_phone" class="form-label" style="font-weight: 600; color: #333; margin-bottom: 10px;">
                    Phone Number <span class="text-danger">*</span>
                  </label>
                  <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror"
                         id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" 
                         placeholder="+234 800 000 0000" required style="border-radius: 8px; padding: 12px; border: 1px solid #ddd; font-size: 0.95rem;">
                  @error('customer_phone')
                    <div class="invalid-feedback d-block" style="color: #dc3545;">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-4">
                  <label for="address" class="form-label" style="font-weight: 600; color: #333; margin-bottom: 10px;">
                    Street Address <span class="text-danger">*</span>
                  </label>
                  <input type="text" class="form-control @error('address') is-invalid @enderror"
                         id="address" name="address" value="{{ old('address') }}" 
                         placeholder="123 Main Street" required style="border-radius: 8px; padding: 12px; border: 1px solid #ddd; font-size: 0.95rem;">
                  @error('address')
                    <div class="invalid-feedback d-block" style="color: #dc3545;">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-4">
                  <label for="city" class="form-label" style="font-weight: 600; color: #333; margin-bottom: 10px;">
                    City <span class="text-danger">*</span>
                  </label>
                  <input type="text" class="form-control @error('city') is-invalid @enderror"
                         id="city" name="city" value="{{ old('city') }}" 
                         placeholder="Lagos" required style="border-radius: 8px; padding: 12px; border: 1px solid #ddd; font-size: 0.95rem;">
                  @error('city')
                    <div class="invalid-feedback d-block" style="color: #dc3545;">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-4">
                  <label for="state" class="form-label" style="font-weight: 600; color: #333; margin-bottom: 10px;">
                    State <span class="text-danger">*</span>
                  </label>
                  <input type="text" class="form-control @error('state') is-invalid @enderror"
                         id="state" name="state" value="{{ old('state') }}" 
                         placeholder="Lagos" required style="border-radius: 8px; padding: 12px; border: 1px solid #ddd; font-size: 0.95rem;">
                  @error('state')
                    <div class="invalid-feedback d-block" style="color: #dc3545;">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-4">
                  <label for="zip" class="form-label" style="font-weight: 600; color: #333; margin-bottom: 10px;">
                    ZIP Code <span class="text-danger">*</span>
                  </label>
                  <input type="text" class="form-control @error('zip') is-invalid @enderror"
                         id="zip" name="zip" value="{{ old('zip') }}" 
                         placeholder="100001" required style="border-radius: 8px; padding: 12px; border: 1px solid #ddd; font-size: 0.95rem;">
                  @error('zip')
                    <div class="invalid-feedback d-block" style="color: #dc3545;">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-4">
                  <label for="country" class="form-label" style="font-weight: 600; color: #333; margin-bottom: 10px;">
                    Country <span class="text-danger">*</span>
                  </label>
                  <input type="text" class="form-control @error('country') is-invalid @enderror"
                         id="country" name="country" value="{{ old('country', 'Nigeria') }}" 
                         placeholder="Nigeria" required style="border-radius: 8px; padding: 12px; border: 1px solid #ddd; font-size: 0.95rem;">
                  @error('country')
                    <div class="invalid-feedback d-block" style="color: #dc3545;">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Save Billing Address Checkbox -->
              <div class="form-check" style="background: #f0f7ff; padding: 15px; border-radius: 8px; margin-top: 20px;">
                <input class="form-check-input" type="checkbox" id="save_billing" name="save_billing" value="1"
                       style="width: 18px; height: 18px; cursor: pointer;">
                <label class="form-check-label" for="save_billing" style="cursor: pointer; margin-left: 8px; color: #333; font-weight: 500;">
                  <i class="fas fa-bookmark" style="color: #667eea; margin-right: 8px;"></i>
                  Save this address for future purchases (we'll remember it for next time)
                </label>
              </div>
            </div>
          </div>

          <!-- Payment Method Section -->
          <div style="background: white; padding: 30px; border-radius: 12px; border: 1px solid #e9ecef; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.06);">
            <div style="display: flex; align-items: center; margin-bottom: 25px;">
              <div style="width: 30px; height: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; margin-right: 15px;">
                2
              </div>
              <h4 class="mb-0" style="font-weight: 700; color: #333;">Select Payment Method</h4>
            </div>

            <div style="display: grid; gap: 15px;">
              <!-- Flutterwave Option -->
              <div style="border: 2px solid #e9ecef; border-radius: 10px; padding: 20px; cursor: pointer; transition: all 0.3s;" 
                   onclick="selectPaymentMethod('flutterwave')" id="flutterwave-card">
                <div style="display: flex; align-items: center;">
                  <input class="form-check-input" type="radio" name="payment_method" id="flutterwave" value="flutterwave" checked style="width: 20px; height: 20px; cursor: pointer; margin-right: 15px;">
                  <div>
                    <label for="flutterwave" style="cursor: pointer; margin: 0; font-weight: 600; color: #333; font-size: 1rem;">
                      <i class="fas fa-credit-card" style="color: #667eea; margin-right: 8px;"></i> Flutterwave
                    </label>
                    <p style="margin: 5px 0 0 0; color: #999; font-size: 0.9rem;">
                      Credit/Debit Card, Bank Transfer, USSD
                    </p>
                  </div>
                  <span style="margin-left: auto; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 6px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">Recommended</span>
                </div>
              </div>

              <!-- Bank Transfer Option -->
              <div style="border: 2px solid #e9ecef; border-radius: 10px; padding: 20px; cursor: pointer; transition: all 0.3s;" 
                   onclick="selectPaymentMethod('bank_transfer')" id="bank_transfer-card">
                <div style="display: flex; align-items: center;">
                  <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer" style="width: 20px; height: 20px; cursor: pointer; margin-right: 15px;">
                  <div>
                    <label for="bank_transfer" style="cursor: pointer; margin: 0; font-weight: 600; color: #333; font-size: 1rem;">
                      <i class="fas fa-university" style="color: #667eea; margin-right: 8px;"></i> Bank Transfer
                    </label>
                    <p style="margin: 5px 0 0 0; color: #999; font-size: 0.9rem;">
                      Direct bank transfer to our account
                    </p>
                  </div>
                </div>
              </div>

              <!-- PayPal Option -->
              <div style="border: 2px solid #e9ecef; border-radius: 10px; padding: 20px; cursor: pointer; transition: all 0.3s;" 
                   onclick="selectPaymentMethod('paypal')" id="paypal-card">
                <div style="display: flex; align-items: center;">
                  <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal" style="width: 20px; height: 20px; cursor: pointer; margin-right: 15px;">
                  <div>
                    <label for="paypal" style="cursor: pointer; margin: 0; font-weight: 600; color: #333; font-size: 1rem;">
                      <i class="fab fa-paypal" style="color: #667eea; margin-right: 8px;"></i> PayPal
                    </label>
                    <p style="margin: 5px 0 0 0; color: #999; font-size: 0.9rem;">
                      Use your PayPal account for secure payment
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Additional Information Section -->
          <div style="background: white; padding: 30px; border-radius: 12px; border: 1px solid #e9ecef; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.06);">
            <div style="display: flex; align-items: center; margin-bottom: 25px;">
              <div style="width: 30px; height: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; margin-right: 15px;">
                3
              </div>
              <h4 class="mb-0" style="font-weight: 700; color: #333;">Order Review</h4>
            </div>

            <div class="mb-4">
              <label for="notes" class="form-label" style="font-weight: 600; color: #333; margin-bottom: 10px;">
                <i class="fas fa-sticky-note" style="color: #667eea;"></i> Order Notes (Optional)
              </label>
              <textarea class="form-control" id="notes" name="notes" rows="3" 
                        placeholder="Add any special instructions or delivery notes..." 
                        style="border-radius: 8px; padding: 12px; border: 1px solid #ddd; font-size: 0.95rem;">{{ old('notes') }}</textarea>
            </div>

            <!-- Terms & Conditions -->
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #667eea;">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="agree_terms" name="terms" required 
                       style="width: 20px; height: 20px; cursor: pointer;">
                <label class="form-check-label" for="agree_terms" style="cursor: pointer; margin-left: 8px; margin-top: 3px;">
                  <strong style="color: #333;">I agree to the </strong>
                  <a href="{{ route('terms') }}" target="_blank" class="link-underline" style="color: #667eea; text-decoration: underline; font-weight: 600;">
                    Terms & Conditions
                  </a>
                  <strong style="color: #333;"> and </strong>
                  <a href="{{ route('policy') }}" target="_blank" class="link-underline" style="color: #667eea; text-decoration: underline; font-weight: 600;">
                    Privacy Policy
                  </a>
                </label>
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="btn w-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 10px; font-weight: 600; padding: 16px; font-size: 1.1rem; transition: all 0.3s; cursor: pointer; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
            <i class="fas fa-lock"></i> Complete & Pay Securely
          </button>
          
          <!-- Security Info -->
          <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e9ecef;">
            <small style="color: #999;">
              <i class="fas fa-shield-alt" style="color: #28a745;"></i>
              Your payment information is secure and encrypted with SSL
            </small>
          </div>
        </form>
      </div>

      <!-- Order Summary Sidebar -->
      <div class="col-lg-4">
        <!-- Sticky Summary Card -->
        <div style="background: white; padding: 30px; border-radius: 12px; border: 1px solid #e9ecef; position: sticky; top: 100px; box-shadow: 0 4px 25px rgba(0,0,0,0.08);">
          <!-- Header with Gradient -->
          <div style="display: flex; align-items: center; margin-bottom: 25px; padding-bottom: 20px; border-bottom: 2px solid #e9ecef;">
            <i class="fas fa-shopping-bag" style="font-size: 1.3rem; color: #667eea; margin-right: 12px;"></i>
            <h4 class="mb-0" style="color: #333; font-weight: 700; letter-spacing: -0.5px;">Order Summary</h4>
          </div>

          <!-- Order Items -->
          <div class="mb-4">
            @if(isset($cart) && is_array($cart) && count($cart) > 0)
              @php $subtotal = 0; @endphp
              @foreach($cart as $item)
                @php
                  $itemPrice = (float)($item['totalPrice'] ?? 0);
                  $itemQty = (int)($item['quantity'] ?? 1);
                  $itemTotal = $itemPrice * $itemQty;
                  $subtotal += $itemTotal;
                @endphp
                <div style="background: #f8f9fa; padding: 16px; border-radius: 10px; margin-bottom: 12px;">
                  <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                    <h6 style="margin: 0; color: #333; font-weight: 600; flex: 1; padding-right: 10px;">{{ $item['name'] ?? 'Product' }}</h6>
                    <span style="color: #667eea; font-weight: 700; white-space: nowrap;">₦{{ number_format($itemTotal, 2) }}</span>
                  </div>
                  <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: #999;">
                    <small>
                      <i class="fas fa-calendar-alt"></i>
                      @switch($item['licenseDuration'] ?? '1year')
                        @case('6months') 6 Months @break
                        @case('1year') 1 Year @break
                        @case('2years') 2 Years @break
                        @default {{ $item['licenseDuration'] ?? 'Unknown' }}
                      @endswitch
                    </small>
                    <small><i class="fas fa-box"></i> × {{ $itemQty }}</small>
                  </div>
                </div>
              @endforeach
            @elseif(isset($portfolio))
              @php $subtotal = (float)$selectedPrice; @endphp
              <div style="background: #f8f9fa; padding: 16px; border-radius: 10px; margin-bottom: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                  <h6 style="margin: 0; color: #333; font-weight: 600; flex: 1; padding-right: 10px;">{{ $portfolio->title }}</h6>
                  <span style="color: #667eea; font-weight: 700; white-space: nowrap;">₦{{ number_format($selectedPrice, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: #999;">
                  <small>
                    <i class="fas fa-calendar-alt"></i>
                    @switch($licenseDuration)
                      @case('6months') 6 Months @break
                      @case('1year') 1 Year @break
                      @case('2years') 2 Years @break
                      @default {{ $licenseDuration }}
                    @endswitch
                  </small>
                  <small><i class="fas fa-box"></i> × 1</small>
                </div>
              </div>
            @else
              @php $subtotal = 99.99; @endphp
              <div style="background: #f8f9fa; padding: 16px; border-radius: 10px; margin-bottom: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                  <h6 style="margin: 0; color: #333; font-weight: 600;">Sample Product</h6>
                  <span style="color: #667eea; font-weight: 700;">₦99.99</span>
                </div>
                <small style="color: #999;"><i class="fas fa-box"></i> Quantity: 1</small>
              </div>
            @endif
          </div>

          <!-- Divider -->
          <div style="height: 1px; background: linear-gradient(90deg, transparent, #e9ecef, transparent); margin: 20px 0;"></div>

          <!-- Pricing Breakdown -->
          <div style="margin-bottom: 20px;">
            @if(isset($cart) && is_array($cart) && count($cart) > 0)
              @php
                $calculatedSubtotal = 0;
                foreach($cart as $item) {
                  $itemPrice = (float)($item['totalPrice'] ?? 0);
                  $itemQty = (int)($item['quantity'] ?? 1);
                  $calculatedSubtotal += ($itemPrice * $itemQty);
                }
              @endphp
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; color: #666;">
                <span style="font-weight: 500;">Subtotal</span>
                <span style="color: #333; font-weight: 600;">₦{{ number_format($calculatedSubtotal, 2) }}</span>
              </div>
            @else
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; color: #666;">
                <span style="font-weight: 500;">Subtotal</span>
                <span style="color: #333; font-weight: 600;">₦{{ isset($subtotal) ? number_format($subtotal, 2) : '0.00' }}</span>
              </div>
            @endif

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; color: #666;">
              <span style="font-weight: 500;">Shipping</span>
              <span style="color: #28a745; font-weight: 700;">
                <i class="fas fa-check-circle"></i> Free
              </span>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; color: #666;">
              <span style="font-weight: 500;">Tax</span>
              <span style="color: #333; font-weight: 600;">₦0.00</span>
            </div>
          </div>

          <!-- Total Section with Gradient -->
          <div style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); padding: 20px; border-radius: 10px; border: 1px solid #e9ecef; margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <span style="color: #333; font-weight: 600; font-size: 1rem;">Total Amount</span>
              @if(isset($cart) && is_array($cart) && count($cart) > 0)
                @php
                  $finalTotal = 0;
                  foreach($cart as $item) {
                    $itemPrice = (float)($item['totalPrice'] ?? 0);
                    $itemQty = (int)($item['quantity'] ?? 1);
                    $finalTotal += ($itemPrice * $itemQty);
                  }
                @endphp
                <h3 style="margin: 0; color: #667eea; font-weight: 700;">₦{{ number_format($finalTotal, 2) }}</h3>
              @else
                <h3 style="margin: 0; color: #667eea; font-weight: 700;">₦{{ isset($selectedPrice) ? number_format($selectedPrice, 2) : (isset($subtotal) ? number_format($subtotal, 2) : '0.00') }}</h3>
              @endif
            </div>
          </div>

          <!-- Trust Badges -->
          <div style="margin-bottom: 20px; padding: 15px; background: #f0f7ff; border-radius: 10px; border: 1px solid #d4e8ff;">
            <h6 style="margin: 0 0 12px 0; color: #333; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
              <i class="fas fa-shield-alt" style="color: #667eea;"></i> Secure & Trusted
            </h6>
            <div style="display: flex; flex-direction: column; gap: 8px;">
              <div style="display: flex; align-items: center; font-size: 0.85rem;">
                <i class="fas fa-lock" style="color: #28a745; margin-right: 8px; min-width: 16px;"></i>
                <span style="color: #555;">SSL Encrypted Payment</span>
              </div>
              <div style="display: flex; align-items: center; font-size: 0.85rem;">
                <i class="fas fa-redo" style="color: #28a745; margin-right: 8px; min-width: 16px;"></i>
                <span style="color: #555;">30-Day Money Back</span>
              </div>
              <div style="display: flex; align-items: center; font-size: 0.85rem;">
                <i class="fas fa-headset" style="color: #28a745; margin-right: 8px; min-width: 16px;"></i>
                <span style="color: #555;">24/7 Support Team</span>
              </div>
            </div>
          </div>

          <!-- FAQ Accordion (collapsible) -->
          <div style="border-top: 1px solid #e9ecef; padding-top: 15px;">
            <div class="accordion accordion-flush" id="checkoutFAQ">
              <div class="accordion-item" style="border: none;">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqOne" style="padding: 0; font-size: 0.9rem; font-weight: 600; color: #667eea;">
                    <i class="fas fa-question-circle" style="margin-right: 8px;"></i> Need Help?
                  </button>
                </h2>
                <div id="faqOne" class="accordion-collapse collapse" data-bs-parent="#checkoutFAQ">
                  <div class="accordion-body" style="padding: 12px 0; border: none; color: #666; font-size: 0.85rem; line-height: 1.6;">
                    <p class="mb-2"><strong>Payment Issues?</strong></p>
                    <p class="mb-3" style="margin-bottom: 8px;">Contact our support team at <a href="mailto:support@skyeface.com" style="color: #667eea;">support@skyeface.com</a></p>
                    <p class="mb-0"><strong>Refund Policy</strong></p>
                    <small>30-day money back guarantee if unsatisfied</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
.checkout-section {
  background: #f9f9f9;
  padding: 30px;
  border-radius: 8px;
  margin-bottom: 20px;
}

.section-title {
  color: #333;
  font-weight: 600;
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 2px solid #667eea;
}

.form-label {
  font-weight: 500;
  color: #333;
  margin-bottom: 8px;
}

.form-control {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 12px;
  transition: all 0.3s ease;
}

.form-control:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-check-input {
  width: 20px;
  height: 20px;
  border: 2px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.form-check-input:checked {
  background-color: #667eea;
  border-color: #667eea;
}

.form-check-input:focus {
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Payment Method Cards */
.payment-card {
  border: 2px solid #e9ecef;
  border-radius: 10px;
  padding: 20px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.payment-card:hover {
  border-color: #d9d9ff;
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}

.payment-card.active {
  border-color: #667eea;
  background-color: #f0f7ff;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
}

/* Submit Button */
.btn-checkout {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 10px;
  font-weight: 600;
  padding: 16px;
  font-size: 1.1rem;
  transition: all 0.3s ease;
  cursor: pointer;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
  width: 100%;
  margin-top: 20px;
}

.btn-checkout:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 25px rgba(102, 126, 234, 0.4);
  color: white;
}

.btn-checkout:active {
  transform: translateY(0);
}

/* Alert Styling */
.alert-checkout {
  border-left: 4px solid #dc3545;
  background: #fff5f5;
  border-radius: 8px;
  padding: 20px;
}

/* Responsive Styles */
@media (max-width: 768px) {
  .checkout-summary {
    position: relative;
    top: 0;
    margin-top: 30px;
  }

  .progress-steps {
    margin-bottom: 30px;
  }

  .progress-steps h6 {
    font-size: 0.85rem;
  }

  .form-control {
    font-size: 16px; /* Prevents zoom on mobile */
  }

  .payment-card {
    padding: 16px;
  }

  .payment-card label {
    font-size: 0.95rem;
  }

  .section-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .section-header .step-circle {
    margin-bottom: 12px;
  }
}

@media (max-width: 576px) {
  .hero-section h1 {
    font-size: 1.8rem;
  }

  .hero-section p {
    font-size: 0.9rem;
  }

  .payment-options {
    grid-gap: 12px;
  }

  .order-summary-container {
    padding: 20px;
  }
}
</style>

<script>
function selectPaymentMethod(method) {
    try {
        console.log('=== SELECT PAYMENT METHOD ===');
        console.log('Method selected:', method);

        // Find and check radio button
        const radio = document.getElementById(method);
        if (!radio) {
            console.warn('⚠️ Radio button not found for method:', method);
        } else {
            radio.checked = true;
            console.log('✓ Radio button checked for:', method);
        }
        
        // Remove active state from all cards
        const allCards = document.querySelectorAll('[id$="-card"]');
        if (allCards && allCards.length > 0) {
            allCards.forEach(card => {
                card.style.borderColor = '#e9ecef';
                card.style.backgroundColor = 'transparent';
                card.style.boxShadow = '0 2px 8px rgba(0,0,0,0.06)';
            });
            console.log('✓ Reset all payment cards');
        }
        
        // Add active state to selected card
        const cardId = method + '-card';
        const card = document.getElementById(cardId);
        if (card) {
            card.style.borderColor = '#667eea';
            card.style.backgroundColor = '#f0f7ff';
            card.style.boxShadow = '0 4px 15px rgba(102, 126, 234, 0.2)';
            console.log('✓ Highlighted payment card:', method);
        } else {
            console.warn('⚠️ Payment card element not found:', cardId);
        }
        
        console.log('✓ Payment method selection completed:', method);
        return true;
    } catch(error) {
        console.error('❌ Payment method selection error:', error);
        alert('❌ Error selecting payment method: ' + error.message);
        return false;
    }
}

function validateCheckoutForm() {
    try {
        console.log('=== VALIDATING CHECKOUT FORM ===');
        
        // Check if checkout form exists
        const checkoutForm = document.getElementById('checkout-form');
        if (!checkoutForm) {
            console.error('❌ Checkout form not found');
            alert('❌ Error: Checkout form not found. Please refresh the page.');
            return false;
        }
        console.log('✓ Checkout form found');
        
        // Check if terms are accepted
        const termsCheckbox = document.getElementById('agree_terms');
        if (!termsCheckbox) {
            console.error('❌ Terms checkbox not found');
            alert('❌ Error: Terms checkbox not found. Please refresh the page.');
            return false;
        }

        if (!termsCheckbox.checked) {
            console.warn('❌ Terms not accepted');
            alert('❌ Please agree to the Terms & Conditions and Privacy Policy');
            termsCheckbox.focus();
            return false;
        }
        console.log('✓ Terms & Conditions accepted');
        
        // Check if payment method is selected
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        if (!paymentMethod) {
            console.warn('❌ No payment method selected');
            alert('❌ Please select a payment method');
            return false;
        }
        console.log('✓ Payment method selected:', paymentMethod.value);
        
        // Check required fields
        const requiredFields = ['customer_name', 'customer_email', 'customer_phone', 'address', 'city', 'state', 'zip', 'country'];
        for (let field of requiredFields) {
            const element = document.getElementById(field);
            if (!element) {
                console.error('❌ Required field element not found:', field);
                alert(`❌ Error: ${field} field not found. Please refresh the page.`);
                return false;
            }

            const value = element.value ? element.value.trim() : '';
            if (!value) {
                console.warn(`❌ Field is empty: ${field}`);
                alert(`❌ Please fill in the ${field.replace(/_/g, ' ')} field`);
                element.focus();
                return false;
            }
            console.log(`✓ Field ${field}:`, value.substring(0, 20) + (value.length > 20 ? '...' : ''));
        }
        
        console.log('✓ All required fields validated');
        console.log('✓ Form validation passed - preparing to submit...');
        console.log('✓ Form action:', checkoutForm.action);
        console.log('✓ Form method:', checkoutForm.method);
        
        // Show loading indicator
        const submitBtn = document.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            console.log('✓ Loading indicator activated');
        }
        
        // All validation passed - allow form to submit naturally
        console.log('✓ Validation complete - submitting form now');
        
        // Use setTimeout to ensure form submission happens after event returns
        setTimeout(function() {
            console.log('✓ Executing form.submit()...');
            try {
                checkoutForm.submit();
                console.log('✓ form.submit() called successfully');
            } catch(submitError) {
                console.error('❌ Error calling form.submit():', submitError);
                alert('Error submitting form. Please try again.');
            }
        }, 100);
        
        return false; // Return false to prevent duplicate submission via default handler
        
    } catch(error) {
        console.error('❌ Form validation error:', error);
        console.error('Error stack:', error.stack);
        alert('❌ Error validating form: ' + error.message);
        return false;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Checkout page loaded');
    
    // Initialize form inputs with better focus states
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.borderColor = '#667eea';
            this.style.boxShadow = '0 0 0 3px rgba(102, 126, 234, 0.1)';
        });
        
        input.addEventListener('blur', function() {
            this.style.borderColor = '#ddd';
            this.style.boxShadow = 'none';
        });
    });
    
    // Initialize payment method cards
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            selectPaymentMethod(this.value);
        });
        
        // Set initial state if checked
        if (radio.checked) {
            selectPaymentMethod(radio.value);
        }
    });
    
    // Add visual feedback for checkbox
    const termsCheckbox = document.getElementById('agree_terms');
    if (termsCheckbox) {
        termsCheckbox.addEventListener('change', function() {
            if (this.checked) {
                this.parentElement.style.background = '#f0f7ff';
            } else {
                this.parentElement.style.background = '#f8f9fa';
            }
        });
    }
    
    // Add hover effects to payment cards
    document.querySelectorAll('[id$="-card"]').forEach(card => {
        card.addEventListener('mouseenter', function() {
            if (!this.querySelector('input[type="radio"]').checked) {
                this.style.borderColor = '#d9d9ff';
                this.style.boxShadow = '0 4px 12px rgba(102, 126, 234, 0.15)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            if (!this.querySelector('input[type="radio"]').checked) {
                this.style.borderColor = '#e9ecef';
                this.style.boxShadow = '0 2px 8px rgba(0,0,0,0.06)';
            }
        });
    });
    
    // Handle form submission with detailed logging
    const form = document.getElementById('checkout-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            try {
                console.log('=== FORM SUBMIT EVENT FIRED ===');
                console.log('Event type:', e.type);
                console.log('Event timestamp:', new Date().toISOString());
                
                // Check if validation already passed
                console.log('✓ Validation returned true, form is submitting');
                
                // Log all form inputs
                const formData = new FormData(form);
                console.log('Form data keys:', Array.from(formData.keys()).length);
                console.log('Form action:', form.action);
                console.log('Form method:', form.method);
                
                for (let [key, value] of formData) {
                    if (key !== '_token' && key !== 'password') {
                        if (key === 'cart') {
                            console.log(`  ✓ ${key}: [CART DATA - ${value.length} chars]`);
                        } else if (typeof value === 'string' && value.length > 50) {
                            console.log(`  ✓ ${key}: ${value.substring(0, 30)}...`);
                        } else {
                            console.log(`  ✓ ${key}: ${value}`);
                        }
                    }
                }
                
                console.log('=== FORM WILL NOW SUBMIT TO SERVER ===');
                console.log('Do NOT stop this submission!');
                
                // The form will submit naturally after this handler completes
                return true;
            } catch(error) {
                console.error('❌ Error in submit event listener:', error);
                console.error('Error details:', error.message);
                e.preventDefault();
                alert('Error processing form. Please try again.');
                return false;
            }
        });
        
        console.log('✓ Form submit event listener attached');
    } else {
        console.error('❌ Checkout form element not found!');
    }
});

// Select a saved billing address and populate form
function selectSavedAddress(addressId, name, email, phone, address, city, state, zip, country) {
    document.getElementById('customer_name').value = name;
    document.getElementById('customer_email').value = email;
    document.getElementById('customer_phone').value = phone;
    document.getElementById('address').value = address;
    document.getElementById('city').value = city;
    document.getElementById('state').value = state;
    document.getElementById('zip').value = zip;
    document.getElementById('country').value = country;
    
    // Select the radio button
    const radio = document.querySelector(`input[name="billing_address_id"][value="${addressId}"]`);
    if (radio) {
        radio.checked = true;
    }
    
    // Uncheck save billing since using saved address
    document.getElementById('save_billing').checked = false;
    
    // Highlight selected address
    document.querySelectorAll('[id^="address-"]').forEach(el => {
        el.style.borderColor = 'transparent';
        el.style.backgroundColor = 'white';
    });
    document.getElementById(`address-${addressId}`).style.borderColor = '#667eea';
    document.getElementById(`address-${addressId}`).style.backgroundColor = '#f8f9fa';
}

// Show the new address form
function showNewAddressForm() {
    document.getElementById('address-form').style.display = 'block';
    document.querySelectorAll('input[name="billing_address_id"]').forEach(radio => {
        radio.checked = false;
    });
    document.getElementById('customer_name').focus();
}

// Load saved addresses when email changes
function loadSavedAddresses(email) {
    if (!email) return;
    
    fetch('{{ route("checkout.getSavedAddresses") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.addresses && data.addresses.length > 0) {
            // Reload page to show saved addresses
            location.reload();
        }
    })
    .catch(error => console.error('Error loading addresses:', error));
}
</script>

@endsection
