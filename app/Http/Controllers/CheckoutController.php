<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Order;
use App\Models\BillingAddress;
use App\Services\PaymentProcessorService;
use App\Services\InvoiceNumberService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page
     */
    public function show(Request $request)
    {
        // Get the active payment processor
        $activeProcessor = PaymentProcessorService::getActiveProcessor();

        // Get saved billing addresses by email if provided
        $savedAddresses = [];
        if ($request->input('email')) {
            $savedAddresses = BillingAddress::where('customer_email', $request->input('email'))->get();
        }

        // Check if cart data is being passed from shopping cart
        $cartData = $request->input('cart');
        $total = $request->input('total');

        if ($cartData) {
            // Shopping cart checkout (multiple items)
            $cart = is_string($cartData) ? json_decode($cartData, true) : $cartData;

            // Ensure total is a number
            $total = is_numeric($total) ? (float)$total : 0;

            // If total wasn't passed, calculate it
            if ($total == 0 && is_array($cart)) {
                foreach ($cart as $item) {
                    $itemPrice = (float)($item['totalPrice'] ?? 0);
                    $quantity = (int)($item['quantity'] ?? 1);
                    $total += ($itemPrice * $quantity);
                }
            }

            return view('shop.checkout', compact('cart', 'total', 'activeProcessor', 'savedAddresses'));
        }

        // Single product checkout (legacy support)
        $portfolioId = $request->input('portfolio_id');
        $licenseDuration = $request->input('license_duration', '1year');
        $portfolio = Portfolio::findOrFail($portfolioId);

        // Calculate total price: base price + license price
        $licensePrices = [
            '6months' => $portfolio->price_6months ?? 0,
            '1year' => $portfolio->price_1year ?? 0,
            '2years' => $portfolio->price_2years ?? 0,
        ];

        $licensePrice = $licensePrices[$licenseDuration] ?? 0;
        $selectedPrice = (float)$portfolio->price + (float)$licensePrice;

        return view('shop.checkout', compact('portfolio', 'licenseDuration', 'selectedPrice', 'licensePrices', 'activeProcessor', 'savedAddresses'));
    }

    /**
     * Place an order and redirect to payment
     */
    public function store(Request $request)
    {
        try {
            // Check if it's a cart order (multiple items)
            $cartData = $request->input('cart');

            if ($cartData) {
                // Shopping cart order validation
                $validated = $request->validate([
                    'billing_address_id' => 'nullable|exists:billing_addresses,id',
                    'customer_name' => 'required|string|max:255',
                    'customer_email' => 'required|email',
                    'customer_phone' => 'required|string|max:20',
                    'address' => 'required|string|max:255',
                    'city' => 'required|string|max:100',
                    'state' => 'required|string|max:100',
                    'zip' => 'required|string|max:20',
                    'country' => 'required|string|max:100',
                    'payment_method' => 'required|in:flutterwave,bank_transfer,paypal',
                    'save_billing' => 'nullable|boolean',
                    'terms' => 'accepted',
                    'cart' => 'required',
                    'total' => 'required|numeric',
                ]);

                // Decode cart if it's a string
                $cart = is_string($cartData) ? json_decode($cartData, true) : $cartData;
                $total = (float)$request->input('total');

                // Save or get billing address
                $billingAddressId = null;
                if ($request->input('save_billing')) {
                    // Save new billing address
                    $billingAddress = BillingAddress::create([
                        'customer_name' => $validated['customer_name'],
                        'customer_email' => $validated['customer_email'],
                        'customer_phone' => $validated['customer_phone'],
                        'address' => $validated['address'],
                        'city' => $validated['city'],
                        'state' => $validated['state'],
                        'zip' => $validated['zip'],
                        'country' => $validated['country'],
                    ]);
                    $billingAddressId = $billingAddress->id;
                } elseif ($validated['billing_address_id'] ?? false) {
                    // Use existing billing address
                    $billingAddressId = $validated['billing_address_id'];
                }

                // Create order
                $order = Order::create([
                    'invoice_number' => InvoiceNumberService::generate(),
                    'billing_address_id' => $billingAddressId,
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'customer_phone' => $validated['customer_phone'],
                    'address' => $validated['address'],
                    'city' => $validated['city'],
                    'state' => $validated['state'],
                    'zip' => $validated['zip'],
                    'country' => $validated['country'],
                    'amount' => $total,
                    'currency' => 'NGN',
                    'status' => 'pending',
                    'payment_method' => $validated['payment_method'],
                    'payment_processor' => PaymentProcessorService::getActiveProcessor(),
                    'cart_items' => json_encode($cart),
                    'notes' => $request->input('notes'),
                ]);

                return redirect()->route('payment.show', $order);
            }

            // Single product order (legacy)
            $validated = $request->validate([
                'billing_address_id' => 'nullable|exists:billing_addresses,id',
                'portfolio_id' => 'required|exists:portfolios,id',
                'license_duration' => 'required|in:6months,1year,2years',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email',
                'customer_phone' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'zip' => 'required|string|max:20',
                'country' => 'required|string|max:100',
                'save_billing' => 'nullable|boolean',
            ]);

            $portfolio = Portfolio::findOrFail($validated['portfolio_id']);

            // Calculate total price: base price + license price
            $licensePrices = [
                '6months' => $portfolio->price_6months ?? 0,
                '1year' => $portfolio->price_1year ?? 0,
                '2years' => $portfolio->price_2years ?? 0,
            ];

            $licensePrice = $licensePrices[$validated['license_duration']] ?? 0;
            $amount = (float)$portfolio->price + (float)$licensePrice;

            // Save or get billing address
            $billingAddressId = null;
            if ($request->input('save_billing')) {
                // Save new billing address
                $billingAddress = BillingAddress::create([
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'customer_phone' => $validated['customer_phone'],
                    'address' => $validated['address'],
                    'city' => $validated['city'],
                    'state' => $validated['state'],
                    'zip' => $validated['zip'],
                    'country' => $validated['country'],
                ]);
                $billingAddressId = $billingAddress->id;
            } elseif ($validated['billing_address_id'] ?? false) {
                // Use existing billing address
                $billingAddressId = $validated['billing_address_id'];
            }

            // Create order
            $order = Order::create([
                'invoice_number' => InvoiceNumberService::generate(),
                'portfolio_id' => $portfolio->id,
                'billing_address_id' => $billingAddressId,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'zip' => $validated['zip'],
                'country' => $validated['country'],
                'amount' => $amount,
                'currency' => 'NGN',
                'status' => 'pending',
                'license_duration' => $validated['license_duration'],
                'payment_processor' => PaymentProcessorService::getActiveProcessor(),
            ]);

            return redirect()->route('payment.show', $order);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create order: ' . $e->getMessage()]);
        }
    }

    /**
     * Get saved billing addresses by customer email (AJAX endpoint)
     */
    public function getSavedAddresses(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $addresses = BillingAddress::where('customer_email', $validated['email'])
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'addresses' => $addresses,
            'count' => $addresses->count(),
        ]);
    }
}

