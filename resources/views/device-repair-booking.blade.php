@extends('layouts.app')

@section('content')
<!-- HERO -->
<div id="home-p" class="home-p pages-head1 text-center">
	<div class="container">
		<h1 class="wow fadeInUp">Quick Device Repair Booking</h1>
		<p>Fast, transparent repair services with instant tracking</p>
	</div>
</div>

<!-- BOOKING PAGE -->
<section class="bg-white" style="padding: 80px 0;">
	<div class="container">
		<div class="row">
			<div class="col-md-8 offset-md-2">

				<!-- Info Box -->
				<div style="background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(40, 167, 69, 0.02) 100%); border-radius: 12px; padding: 25px; margin-bottom: 40px; border-left: 5px solid #28a745;">
					<h5 style="color: #28a745; font-weight: 700; margin-bottom: 12px;"><i class="fa fa-info-circle mr-2"></i>How It Works</h5>
					<ul style="list-style: none; padding: 0; color: #555; line-height: 1.8; margin: 0;">
						<li><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><strong>Fill Out Form:</strong> Tell us about your device and issue</li>
						<li><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><strong>See Diagnosis Fee:</strong> Price displays instantly based on device type</li>
						<li><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><strong>Get Tracking Number:</strong> Receive instant confirmation with unique tracking ID</li>
						<li><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><strong>Track Status:</strong> Monitor repair progress in real-time</li>
					</ul>
				</div>

				<!-- Booking Form -->
				<form id="repairBookingForm" onsubmit="submitRepairBooking(event)" style="background: white; border-radius: 12px; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">

					<!-- Row 1: Name and Email -->
					<div class="form-row" style="margin-bottom: 25px;">
						<div class="form-group col-md-6">
							<label for="repair_name" style="font-weight: 600; color: #222; margin-bottom: 8px;">Full Name <span style="color: #dc3545;">*</span></label>
							<input type="text" class="form-control" id="repair_name" name="name" placeholder="John Doe" required style="border: 1px solid #ddd; border-radius: 8px; padding: 12px;">
						</div>
						<div class="form-group col-md-6">
							<label for="repair_email" style="font-weight: 600; color: #222; margin-bottom: 8px;">Email Address <span style="color: #dc3545;">*</span></label>
							<input type="email" class="form-control" id="repair_email" name="email" placeholder="john@example.com" required style="border: 1px solid #ddd; border-radius: 8px; padding: 12px;">
						</div>
					</div>

					<!-- Row 2: Phone and Device Type -->
					<div class="form-row" style="margin-bottom: 25px;">
						<div class="form-group col-md-6">
							<label for="repair_phone" style="font-weight: 600; color: #222; margin-bottom: 8px;">Phone Number <span style="color: #dc3545;">*</span></label>
							<input type="tel" class="form-control" id="repair_phone" name="phone" placeholder="+1 (555) 123-4567" required style="border: 1px solid #ddd; border-radius: 8px; padding: 12px;">
						</div>
						<div class="form-group col-md-6">
							<label for="repair_device_type" style="font-weight: 600; color: #222; margin-bottom: 8px;">Device Type <span style="color: #dc3545;">*</span></label>
							<select class="form-control" id="repair_device_type" name="device_type" required style="border: 1px solid #ddd; border-radius: 8px; padding: 12px;">
								<option value="">-- Select Device Type --</option>
								<option value="Laptop">Laptop</option>
								<option value="Desktop Computer">Desktop Computer</option>
								<option value="Mobile Phone">Mobile Phone</option>
								<option value="Tablet">Tablet</option>
								<option value="Printer">Printer</option>
								<option value="Other">Other Electronic Device</option>
							</select>
						</div>
					</div>

					<!-- Row 3: Device Brand and Model -->
					<div class="form-row" style="margin-bottom: 25px;">
						<div class="form-group col-md-6">
							<label for="repair_brand" style="font-weight: 600; color: #222; margin-bottom: 8px;">Brand <span style="color: #dc3545;">*</span></label>
							<input type="text" class="form-control" id="repair_brand" name="brand" placeholder="e.g., Apple, Dell, Samsung" required style="border: 1px solid #ddd; border-radius: 8px; padding: 12px;">
						</div>
						<div class="form-group col-md-6">
							<label for="repair_model" style="font-weight: 600; color: #222; margin-bottom: 8px;">Model <span style="color: #dc3545;">*</span></label>
							<input type="text" class="form-control" id="repair_model" name="model" placeholder="e.g., MacBook Pro, Pixel 6" required style="border: 1px solid #ddd; border-radius: 8px; padding: 12px;">
						</div>
					</div>

					<!-- Issue Description -->
					<div class="form-group" style="margin-bottom: 25px;">
						<label for="repair_issue" style="font-weight: 600; color: #222; margin-bottom: 8px;">Issue Description <span style="color: #dc3545;">*</span></label>
						<textarea class="form-control" id="repair_issue" name="issue_description" rows="4" placeholder="Describe the problem in detail (minimum 10 characters)..." required style="border: 1px solid #ddd; border-radius: 8px; padding: 12px; resize: vertical;"></textarea>
						<small style="color: #999;">Minimum 10 characters required</small>
					</div>

					<!-- Urgency -->
					<div class="form-group" style="margin-bottom: 25px;">
						<label for="repair_urgency" style="font-weight: 600; color: #222; margin-bottom: 8px;">Repair Urgency</label>
						<select class="form-control" id="repair_urgency" name="urgency" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px;">
							<option value="Normal">Normal (5-7 days)</option>
							<option value="Express">Express (2-3 days)</option>
							<option value="Urgent">Urgent (Next day)</option>
						</select>
					</div>

					<!-- Estimated Cost Display -->
					<div style="background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(40, 167, 69, 0.05) 100%); border-radius: 12px; padding: 20px; margin-bottom: 25px; border-left: 4px solid #28a745; display: none;" id="priceDisplay">
						<div style="display: flex; justify-content: space-between; align-items: center;">
							<div>
								<p style="margin: 0; color: #666; font-size: 14px; font-weight: 500;">Estimated Diagnosis Fee</p>
								<p style="margin: 5px 0 0 0; color: #222; font-size: 12px;">Base cost (may vary after diagnosis)</p>
							</div>
							<div style="text-align: right;">
								<p style="margin: 0; color: #28a745; font-size: 28px; font-weight: 700;" id="priceAmount">$0.00</p>
								<p style="margin: 5px 0 0 0; color: #999; font-size: 12px;">+ Additional parts cost if needed</p>
							</div>
						</div>
						<input type="hidden" id="repair_cost_estimate" name="cost_estimate" value="0">
					</div>

					<!-- Message Display -->
					<div id="repairMessage" style="margin-bottom: 20px; display: none; overflow: visible;" class="alert"></div>

					<!-- Submit Button -->
					<div style="text-align: center;">
						<button type="submit" id="repairSubmitBtn" class="btn" style="background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); color: white; border: none; padding: 14px 50px; font-weight: 600; border-radius: 8px; cursor: pointer; font-size: 16px; transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 8px 20px rgba(40, 167, 69, 0.3)';" onmouseout="this.style.boxShadow='none';">
							<i class="fa fa-check mr-2"></i>Submit Repair Request
						</button>
					</div>
				</form>

				<!-- Tracking Info Box -->
				<div style="margin-top: 40px; background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(40, 167, 69, 0.02) 100%); border-radius: 12px; padding: 30px; border-left: 5px solid #28a745;">
					<h5 style="color: #28a745; font-weight: 700; margin-bottom: 15px;"><i class="fa fa-info-circle mr-2"></i>After Booking</h5>
					<ul style="list-style: none; padding: 0; color: #555; line-height: 1.8; margin: 0;">
						<li><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><strong>Instant Tracking Number:</strong> You'll receive a unique invoice/ticket number immediately after booking</li>
						<li><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><strong>Email Confirmation:</strong> A detailed confirmation will be sent to your email with repair details</li>
						<li><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><strong>Track Progress:</strong> Use your tracking number to check repair status and estimated completion time</li>
						<li><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><strong>Invoice Management:</strong> Your invoice number serves as your ticket to track every stage of the repair process</li>
					</ul>
				</div>

				<!-- Track Existing Repair Link -->
				<div style="margin-top: 30px; text-align: center; padding: 20px;">
					<p style="color: #666; margin-bottom: 15px;">Already have a tracking number?</p>
					<a href="{{ route('repairs.track.page') }}" class="btn" style="background: #f0f0f0; color: #222; border: none; padding: 10px 30px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block;">
						<i class="fa fa-search mr-2"></i>Track Your Repair
					</a>
				</div>

			</div>
		</div>
	</div>
</section>

<script>
	// Device repair pricing based on type
	const repairPricing = {
		'Laptop': 35.00,
		'Desktop Computer': 30.00,
		'Mobile Phone': 25.00,
		'Tablet': 28.00,
		'Printer': 40.00,
		'Other': 50.00
	};

	// Update price when device type changes
	function updateRepairPrice() {
		const deviceType = document.getElementById('repair_device_type').value;
		const priceDisplay = document.getElementById('priceDisplay');
		const priceAmount = document.getElementById('priceAmount');
		const costInput = document.getElementById('repair_cost_estimate');

		if (deviceType && repairPricing[deviceType]) {
			const price = repairPricing[deviceType];
			priceAmount.textContent = '$' + price.toFixed(2);
			costInput.value = price;
			priceDisplay.style.display = 'block';
		} else {
			priceDisplay.style.display = 'none';
			costInput.value = '0';
		}
	}

	// Add event listener to device type dropdown
	document.addEventListener('DOMContentLoaded', function() {
		const deviceTypeSelect = document.getElementById('repair_device_type');
		if (deviceTypeSelect) {
			deviceTypeSelect.addEventListener('change', updateRepairPrice);
		}
	});

	async function submitRepairBooking(event) {
		event.preventDefault();

		const form = document.getElementById('repairBookingForm');
		const messageDiv = document.getElementById('repairMessage');
		const submitButton = form.querySelector('button[type="submit"]');

		const payload = {
			name: document.getElementById('repair_name').value,
			email: document.getElementById('repair_email').value,
			phone: document.getElementById('repair_phone').value,
			device_type: document.getElementById('repair_device_type').value,
			brand: document.getElementById('repair_brand').value,
			model: document.getElementById('repair_model').value,
			issue_description: document.getElementById('repair_issue').value,
			urgency: document.getElementById('repair_urgency').value,
			cost_estimate: parseFloat(document.getElementById('repair_cost_estimate').value) || 0,
		};

		try {
			submitButton.disabled = true;
			submitButton.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i>Processing...';

			let csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : null;
			if (!csrfToken) {
				const tokenInput = document.querySelector('input[name="_token"]');
				csrfToken = tokenInput ? tokenInput.value : null;
			}

			const response = await fetch('{{ route("repairs.store") }}', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': csrfToken || ''
				},
				body: JSON.stringify(payload)
			});

			const data = await response.json().catch(() => null);
			messageDiv.style.display = 'block';
			messageDiv.innerHTML = '';

			// Check if response has success data (regardless of status code)
			if (data && data.success) {
				const trackingNumber = data.tracking_number || data.invoice_number;
				const repairId = data.repair_id;

				console.log('Success Response:', data);
				console.log('Repair ID:', repairId);
				console.log('Message Div:', messageDiv);

				// Hide everything except message
				document.getElementById('repairBookingForm').style.display = 'none';
				document.getElementById('repairSubmitBtn').style.display = 'none';
				
				// Find and hide tracking info box
				const infoBoxes = document.querySelectorAll('[style*="margin-top: 40px"]');
				infoBoxes.forEach(box => {
					if (box.textContent.includes('After Booking')) {
						box.style.display = 'none';
					}
				});
				
				// Find and hide track existing repair link
				const trackLinks = document.querySelectorAll('[style*="margin-top: 30px"]');
				trackLinks.forEach(link => {
					if (link.textContent.includes('Already have a tracking')) {
						link.style.display = 'none';
					}
				});

				// Always show payment buttons with inline styles
				const paymentButtonsHTML = `
					<div style="display: flex; gap: 10px; justify-content: center; margin-top: 20px; flex-wrap: wrap; width: 100%; align-items: center;">
						<a href="/repairs/${repairId}/payment" class="btn btn-success" style="padding: 14px 40px; font-weight: 600; border-radius: 8px; text-decoration: none; font-size: 15px; background: linear-gradient(135deg, #28a745 0%, #1fa935 100%) !important; color: white !important; border: none !important; display: inline-block !important; cursor: pointer; transition: all 0.3s ease;">
							<i class="fa fa-credit-card mr-2"></i>Pay Now for Diagnosis
						</a>
						<a href="/" class="btn btn-secondary" style="padding: 14px 40px; font-weight: 600; border-radius: 8px; text-decoration: none; font-size: 15px; background: #f0f0f0 !important; color: #222 !important; border: 1px solid #ddd !important; display: inline-block !important;">
							<i class="fa fa-arrow-right mr-2"></i>Pay Later
						</a>
					</div>
				`;

				messageDiv.style.display = 'block';
				messageDiv.className = 'alert alert-success';
				messageDiv.innerHTML = `
					<div style="padding: 20px; border-radius: 8px;">
						<div style="text-align: center; margin-bottom: 20px;">
							<i class="fa fa-check-circle" style="color: #28a745; font-size: 40px;"></i>
							<h4 style="color: #28a745; margin-top: 10px; margin-bottom: 5px;">Booking Confirmed!</h4>
							<p style="color: #666; margin: 0;">Your device repair has been scheduled successfully.</p>
						</div>
						<div style="background: rgba(40, 167, 69, 0.15); padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #28a745;">
							<h5 style="color: #28a745; margin-top: 0; margin-bottom: 8px;">YOUR TRACKING NUMBER:</h5>
							<div style="font-size: 28px; font-weight: 700; color: #28a745; letter-spacing: 2px; font-family: 'Courier New', monospace; margin-bottom: 10px;">${trackingNumber}</div>
							<small style="color: #666;">Save this number to track your repair progress</small>
						</div>
						<div style="background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
							<small style="color: #666;"><strong>Invoice Number:</strong> ${data.invoice_number}</small>
							<br><small style="color: #666; margin-top: 5px; display: block;">A confirmation email has been sent to <strong>${data.repair.customer_email}</strong></small>
						</div>
						<div style="background: rgba(13, 110, 253, 0.1); padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #0d6efd;">
							<h6 style="color: #0d6efd; margin-top: 0; margin-bottom: 8px;"><i class="fa fa-info-circle mr-2"></i>Next Steps:</h6>
							<ul style="margin: 0; padding-left: 20px; color: #666; font-size: 14px;">
								<li>Pay the <strong>&#8358;${parseFloat(data.repair.cost_estimate).toLocaleString('en-US', {minimumFractionDigits: 2})}</strong> diagnosis fee to confirm your booking</li>
								<li>Bring your device to our service center</li>
								<li>Our technicians will diagnose the issue within 24 hours</li>
								<li>Track your repair progress using your tracking number</li>
							</ul>
						</div>
						<div style="background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(40, 167, 69, 0.02) 100%); padding: 20px; border-radius: 8px; margin-bottom: 20px;">
							<h6 style="color: #28a745; margin-top: 0; margin-bottom: 12px; font-weight: 600;"><i class="fa fa-lock mr-2"></i>Secure Payment</h6>
							<small style="color: #666;">Click "Pay Now" to proceed to our secure payment gateway. Your payment information is protected.</small>
						</div>
						<hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">
						${paymentButtonsHTML}
					</div>
				`;

				// Scroll to message
				setTimeout(() => {
					messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
				}, 300);
			} else {
				// Error response
				let errHtml = '';
				if (data && data.errors) {
					for (const key in data.errors) {
						errHtml += `<div><i class="fa fa-exclamation-circle mr-2"></i>${data.errors[key].join('<br>')}</div>`;
					}
				} else if (data && data.message) {
					errHtml = `<div><i class="fa fa-exclamation-circle mr-2"></i>${data.message}</div>`;
				} else {
					errHtml = '<div><i class="fa fa-exclamation-circle mr-2"></i>Unable to submit repair request. Please try again later.</div>';
				}
				messageDiv.className = 'alert alert-danger';
				messageDiv.innerHTML = errHtml;
				submitButton.disabled = false;
				submitButton.innerHTML = '<i class="fa fa-check mr-2"></i>Submit Repair Request';
			}
		} catch (error) {
			console.error('Error:', error);
			messageDiv.style.display = 'block';
			messageDiv.className = 'alert alert-danger';
			messageDiv.innerHTML = '<div><i class="fa fa-exclamation-circle mr-2"></i>Network error. Please check your connection and try again.</div>';
			submitButton.disabled = false;
			submitButton.innerHTML = '<i class="fa fa-check mr-2"></i>Submit Repair Request';
		}
	}
</script>

@endsection
