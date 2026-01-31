@extends('layouts.app')

@section('content')
<style>
		body {
			background-color: #f5f5f5;
		}
		.payment-section {
			padding: 60px 0;
		}
		.summary-card {
			background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
			border-left: 5px solid #28a745;
			border-radius: 8px;
			padding: 30px;
			margin-bottom: 30px;
			box-shadow: 0 2px 10px rgba(0,0,0,0.05);
		}
		.summary-card h4 {
			color: #28a745;
			margin-bottom: 20px;
			font-weight: 600;
		}
		.summary-row {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 12px 0;
			border-bottom: 1px solid #e9ecef;
		}
		.summary-row:last-child {
			border-bottom: none;
		}
		.summary-row strong {
			color: #333;
			font-weight: 600;
		}
		.summary-row span {
			color: #666;
		}
		.amount-row {
			background: linear-gradient(135deg, #28a745 0%, #1fa935 100%);
			color: white;
			padding: 15px;
			border-radius: 8px;
			margin-top: 15px;
		}
		.amount-row strong {
			color: white;
			font-size: 16px;
		}
		.amount-row .price {
			font-size: 28px;
			font-weight: 700;
			color: white;
		}
		.processor-info {
			background: #f8f9fa;
			border-left: 4px solid #00d795;
			border-radius: 8px;
			padding: 20px;
			margin-bottom: 30px;
		}
		.processor-info h6 {
			color: #00d795;
			margin-bottom: 10px;
			font-weight: 600;
		}
		.processor-info.flutterwave {
			border-left-color: #ff6b00;
		}
		.processor-info.flutterwave h6 {
			color: #ff6b00;
		}
		.payment-container {
			background: white;
			border-radius: 8px;
			padding: 40px;
			box-shadow: 0 2px 15px rgba(0,0,0,0.08);
		}
		.payment-btn {
			background: linear-gradient(135deg, #28a745 0%, #1fa935 100%);
			color: white;
			border: none;
			padding: 14px 50px;
			font-weight: 600;
			border-radius: 6px;
			cursor: pointer;
			font-size: 16px;
			transition: all 0.3s ease;
			display: inline-block;
			margin-bottom: 15px;
		}
		.payment-btn:hover {
			box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
			transform: translateY(-2px);
			color: white;
			text-decoration: none;
		}
		.payment-btn:disabled {
			opacity: 0.6;
			cursor: not-allowed;
		}
		.loading-spinner {
			display: none;
			text-align: center;
			padding: 40px;
		}
		.loading-spinner i {
			font-size: 40px;
			color: #28a745;
		}
		.loading-spinner p {
			color: #666;
			margin-top: 15px;
		}
		.security-info {
			margin-top: 30px;
			padding: 20px;
			background: #f0f7f1;
			border-radius: 8px;
			text-align: center;
			border: 1px solid #d4edda;
		}
		.security-info p {
			color: #155724;
			margin: 0;
			font-weight: 600;
		}
		.security-info small {
			color: #28a745;
		}
		.cancel-link {
			color: #999;
			text-decoration: none;
			font-size: 14px;
		}
		.cancel-link:hover {
			color: #666;
			text-decoration: underline;
		}
		.page-header {
			background: linear-gradient(135deg, #28a745 0%, #1fa935 100%);
			color: white;
			padding: 60px 0;
			text-align: center;
			margin-bottom: 40px;
		}
		.page-header h1 {
			font-size: 36px;
			font-weight: 700;
			margin-bottom: 10px;
		}
		.page-header p {
			font-size: 18px;
			opacity: 0.9;
		}
		.badge-urgency {
			background: #28a745;
			color: white;
			padding: 5px 12px;
			border-radius: 20px;
			font-size: 12px;
			font-weight: 600;
			display: inline-block;
		}
	</style>
</head>
<body>

<!-- PAYMENT MODAL -->
<div id="paymentModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); color: white; border: none;">
				<h5 class="modal-title" style="color: white;">
					<i class="fa fa-lock" style="margin-right: 10px;"></i>Repair Payment
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<!-- Modal Body -->
			<div class="modal-body" style="padding: 40px;">
				
				<!-- Order Summary -->
				<div class="summary-card">
					<h4><i class="fa fa-check-circle" style="margin-right: 10px;"></i>Repair Booking Summary</h4>
					
					<div class="summary-row">
						<strong>Tracking Number:</strong>
						<span style="color: #28a745; font-weight: 700; font-size: 16px; font-family: 'Courier New', monospace;">{{ $repair->invoice_number }}</span>
					</div>
					<div class="summary-row">
						<strong>Device:</strong>
						<span>{{ $repair->device_brand }} {{ $repair->device_model }} ({{ $repair->device_type }})</span>
					</div>
					<div class="summary-row">
						<strong>Issue:</strong>
						<span>{{ Str::limit($repair->issue_description, 50) }}</span>
					</div>
					<div class="summary-row">
						<strong>Repair Urgency:</strong>
						<span><span class="badge-urgency">{{ ucfirst($repair->urgency) }}</span></span>
					</div>
					<div class="amount-row">
						<div style="display: flex; justify-content: space-between; align-items: center;">
							<strong>
								@if($repair->cost_actual && $repair->cost_actual > 0)
									Total Repair Cost:
								@else
									Diagnosis Fee:
								@endif
							</strong>
							<span class="price">
								@if($repair->cost_actual && $repair->cost_actual > 0)
									₦{{ number_format($repair->cost_actual, 2) }}
								@else
									₦{{ number_format($repair->cost_estimate, 2) }}
								@endif
							</span>
						</div>
					</div>

					<small style="color: #666; display: block; margin-top: 15px;">
						<i class="fa fa-info-circle" style="margin-right: 5px;"></i>
						@if($repair->cost_actual && $repair->cost_actual > 0)
							This is the final repair cost. Payment completes your repair order.
						@else
							This is the diagnosis fee to examine and assess your device. Actual repair costs will be provided after diagnosis is complete.
						@endif
					</small>
				</div>

				<!-- Payment Processor Info -->
				@if($processor === 'paystack')
					<div class="processor-info">
						<h6><i class="fa fa-credit-card" style="margin-right: 8px;"></i>Secure Payment with Paystack</h6>
						<small style="color: #666;">
							Your payment will be processed securely through Paystack. You can pay with your debit card, USSD, Bank Transfer, or Mobile Money.
						</small>
					</div>
				@else
					<div class="processor-info flutterwave">
						<h6><i class="fa fa-credit-card" style="margin-right: 8px;"></i>Secure Payment with Flutterwave</h6>
						<small style="color: #666;">
							Your payment will be processed securely through Flutterwave. Supports multiple payment methods including cards, transfers, and wallets.
						</small>
					</div>
				@endif

				<!-- Payment Buttons -->
				<div id="paymentButtons" style="display: block; text-align: center;">
					<button id="proceedPaymentBtn" class="payment-btn">
						<i class="fa fa-lock" style="margin-right: 8px;"></i>Proceed to Payment
					</button>
				</div>

				<!-- Loading Spinner -->
				<div id="loadingSpinner" class="loading-spinner">
					<i class="fa fa-spinner fa-spin"></i>
					<p>Initializing secure payment gateway...</p>
				</div>

				<!-- Security Info -->
				<div class="security-info">
					<p>
						<i class="fa fa-lock" style="margin-right: 8px;"></i>
						Your payment information is secure and encrypted
					</p>
					<small>
						Payments are processed by PCI DSS Level 1 certified payment providers
					</small>
				</div>

			</div>

			<!-- Modal Footer -->
			<div class="modal-footer" style="border-top: 1px solid #e9ecef;">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
	const repair = @json($repair);
	const processor = '{{ $processor }}';
	const publicKey = '{{ $publicKey }}';
	const amount = parseFloat('{{ $amount }}') * 100; // Convert to kobo/pesewas
	const currency = '{{ $currency }}';

	// Open modal on page load
	document.addEventListener('DOMContentLoaded', function() {
		const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
		paymentModal.show();
		
		const proceedBtn = document.getElementById('proceedPaymentBtn');
		proceedBtn.addEventListener('click', initiatePayment);
	});

	function initiatePayment() {
		const proceedBtn = document.getElementById('proceedPaymentBtn');
		const loadingSpinner = document.getElementById('loadingSpinner');
		const paymentButtons = document.getElementById('paymentButtons');

		proceedBtn.disabled = true;
		paymentButtons.style.opacity = '0.6';
		loadingSpinner.style.display = 'block';

		try {
			if (processor === 'paystack') {
				initializePaystackPayment();
			} else {
				initializeFlutterwavePayment();
			}
		} catch (error) {
			console.error('Payment error:', error);
			loadingSpinner.style.display = 'none';
			paymentButtons.style.opacity = '1';
			proceedBtn.disabled = false;
			alert('Error initializing payment: ' + error.message);
		}
	}

	function initializePaystackPayment() {
		// Load Paystack JS
		const script = document.createElement('script');
		script.src = 'https://js.paystack.co/v1/inline.js';
		const customRef = 'REPAIR-' + repair.id + '-' + Math.floor(Date.now() / 1000);
		
		script.onload = function() {
			console.log('Initiating Paystack payment', {
				email: '{{ $repair->customer_email }}',
				amount: amount,
				currency: currency,
				customRef: customRef,
				repair_id: repair.id,
			});

			const handler = PaystackPop.setup({
				key: publicKey,
				email: '{{ $repair->customer_email }}',
				amount: amount,
				currency: currency,
				ref: customRef,
				firstname: '{{ $repair->customer_name }}',
				lastname: '',
				phone: '{{ $repair->customer_phone }}',
				metadata: {
					repair_id: repair.id,
					invoice_number: repair.invoice_number,
					type: 'repair',
					custom_ref: customRef,
				},
				onClose: function() {
					console.log('Paystack payment modal closed');
					document.getElementById('loadingSpinner').style.display = 'none';
					document.getElementById('paymentButtons').style.opacity = '1';
					document.getElementById('proceedPaymentBtn').disabled = false;
					alert('Payment window closed.');
				},
				callback: function(response) {
					// Payment successful - use our custom ref from metadata
					console.log('Paystack callback response:', response);
					const ref = customRef;
					console.log('Redirecting with ref:', ref);
					window.location.href = '/repairs/paystack-callback?reference=' + ref + '&repair_id=' + repair.id;
				}
			});
			handler.openIframe();
		};
		document.head.appendChild(script);
	}

	function initializeFlutterwavePayment() {
		// Load Flutterwave JS
		const script = document.createElement('script');
		script.src = 'https://checkout.flutterwave.com/v3.js';
		const customRef = 'REPAIR-' + repair.id + '-' + Math.floor(Date.now() / 1000);
		
		script.onload = function() {
			console.log('Initiating Flutterwave payment', {
				tx_ref: customRef,
				amount: parseFloat('{{ $amount }}'),
				currency: currency,
				repair_id: repair.id,
			});

			FlutterwaveCheckout({
				public_key: publicKey,
				tx_ref: customRef,
				amount: parseFloat('{{ $amount }}'),
				currency: currency,
				payment_options: 'card,mobilemoney,ussd',
				customer: {
					email: '{{ $repair->customer_email }}',
					phone_number: '{{ $repair->customer_phone }}',
					name: '{{ $repair->customer_name }}'
				},
				customizations: {
					title: 'Device Repair Diagnosis Fee',
					description: 'Invoice: ' + repair.invoice_number,
					logo: '{{ asset("images/logo.png") }}'
				},
				callback: function(data) {
					console.log('Flutterwave callback response:', data);
					if (data.status === 'successful') {
						// Payment successful - use our custom ref
						console.log('Flutterwave payment successful, redirecting with ref:', customRef);
						window.location.href = '/repairs/flutterwave-callback?transaction_id=' + data.transaction_id + '&tx_ref=' + customRef + '&repair_id=' + repair.id;
					} else {
						console.error('Flutterwave payment failed:', data);
						document.getElementById('loadingSpinner').style.display = 'none';
						document.getElementById('paymentButtons').style.opacity = '1';
						document.getElementById('proceedPaymentBtn').disabled = false;
						alert('Payment failed: ' + (data.message || 'Unknown error'));
					}
				},
				onclose: function() {
					console.log('Flutterwave payment modal closed');
					document.getElementById('loadingSpinner').style.display = 'none';
					document.getElementById('paymentButtons').style.opacity = '1';
					document.getElementById('proceedPaymentBtn').disabled = false;
					alert('Payment window closed.');
				}
			});
		};
		script.onerror = function() {
			document.getElementById('loadingSpinner').style.display = 'none';
			document.getElementById('paymentButtons').style.opacity = '1';
			document.getElementById('proceedPaymentBtn').disabled = false;
			alert('Error loading payment gateway. Please try again.');
		};
		document.head.appendChild(script);
	}
</script>
