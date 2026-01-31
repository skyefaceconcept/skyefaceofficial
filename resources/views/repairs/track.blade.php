<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Track Your Device Repair — {{ config('company.name') }}">
	<meta name="author" content="{{ config('company.name') }}">
	<title>Track Repair - {{ config('company.name') }}</title>
	<link rel="shortcut icon" href="{{ \App\Helpers\CompanyHelper::favicon() }}">
	<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i" rel="stylesheet">
	<link href="{{ asset('buzbox/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('buzbox/font-awesome-4.7.0/css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('buzbox/css/animate/animate.min.css') }}">
	<link rel="stylesheet" href="{{ asset('buzbox/css/style.css') }}">
	<style>
		.timeline {
			position: relative;
			padding: 40px 0;
		}
		.timeline-step {
			display: flex;
			margin-bottom: 30px;
			position: relative;
		}
		.timeline-marker {
			width: 50px;
			height: 50px;
			background: #28a745;
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			color: white;
			font-weight: bold;
			font-size: 18px;
			flex-shrink: 0;
			box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
		}
		.timeline-marker.completed {
			background: #28a745;
		}
		.timeline-marker.pending {
			background: #ccc;
		}
		.timeline-marker.active {
			background: #ffc107;
			animation: pulse 1.5s infinite;
		}
		@keyframes pulse {
			0%, 100% { box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3); }
			50% { box-shadow: 0 4px 20px rgba(255, 193, 7, 0.6); }
		}
		.timeline-content {
			margin-left: 30px;
			flex: 1;
		}
		.timeline-content h5 {
			margin: 0 0 8px 0;
			color: #222;
			font-weight: 600;
		}
		.timeline-content p {
			margin: 0;
			color: #666;
			font-size: 14px;
		}
		.timeline-content small {
			color: #999;
		}
		.progress-container {
			background: #f0f0f0;
			border-radius: 10px;
			padding: 20px;
			margin-bottom: 30px;
		}
	</style>
</head>
<body id="page-top">
	<header>
		@include('partials.top-nav')
		@include('partials.navbar')
	</header>

	<!-- HERO -->
	<div id="home-p" class="home-p pages-head1 text-center">
		<div class="container">
			<h1 class="wow fadeInUp">Track Your Device Repair</h1>
			<p>Monitor the progress of your repair in real-time</p>
		</div>
	</div>

	<!-- TRACKING PAGE -->
	<section class="bg-white" style="padding: 80px 0;">
		<div class="container">
			<div class="row">
				<div class="col-md-10 offset-md-1">

					<!-- Search Section -->
					<div style="background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(40, 167, 69, 0.05) 100%); border-radius: 12px; padding: 30px; margin-bottom: 50px; border: 1px solid rgba(40, 167, 69, 0.1);">
						<h4 style="margin-bottom: 20px; color: #222; font-weight: 600;">Enter Your Tracking Number</h4>
						<div class="input-group" style="margin-bottom: 15px;">
							<input type="text" id="trackingInput" class="form-control" placeholder="e.g., REP-ABC-20260121-0001" style="border: 2px solid #ddd; border-radius: 8px; padding: 12px; font-size: 16px;">
							<div class="input-group-append">
								<button class="btn" onclick="searchRepair()" style="background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); color: white; border: none; padding: 0 30px; font-weight: 600; border-radius: 0 8px 8px 0; cursor: pointer;">
									<i class="fa fa-search mr-2"></i>Track Status
								</button>
							</div>
						</div>
						<small style="color: #666;">You can find your tracking number in your booking confirmation email</small>
					</div>

					<!-- Repair Status Display -->
					<div id="repairDisplay" style="display: none;">

						<!-- Repair Info -->
						<div style="background: #f8f9fa; border-radius: 12px; padding: 25px; margin-bottom: 40px; border-left: 5px solid #28a745;">
							<div class="row">
								<div class="col-md-6">
									<p style="margin: 0 0 15px 0; color: #666;"><strong>Tracking Number:</strong> <span id="trackingNum" style="color: #28a745; font-size: 16px; font-weight: 700;"></span></p>
									<p style="margin: 0 0 15px 0; color: #666;"><strong>Device:</strong> <span id="deviceInfo"></span></p>
									<p style="margin: 0 0 15px 0; color: #666;"><strong>Status:</strong> <span id="repairStatus" style="padding: 5px 12px; border-radius: 20px; background: #fff3cd; color: #856404;"></span></p>
									<p style="margin: 0; color: #666;"><strong>Urgency:</strong> <span id="urgencyLevel"></span></p>
								</div>
								<div class="col-md-6">
									<p style="margin: 0 0 15px 0; color: #666;"><strong>Customer Name:</strong> <span id="customerName"></span></p>
									<p style="margin: 0 0 15px 0; color: #666;"><strong>Email:</strong> <span id="customerEmail"></span></p>
									<p style="margin: 0 0 15px 0; color: #666;"><strong>Phone:</strong> <span id="customerPhone"></span></p>
									<p style="margin: 0 0 15px 0; color: #666;"><strong>Repair Cost:</strong> <span id="repairCost" style="color: #28a745; font-weight: 700; font-size: 16px;"></span></p>
									<p style="margin: 0; color: #666;"><strong>Payment Status:</strong> <span id="paymentStatus" style="padding: 5px 12px; border-radius: 20px; background: #fff3cd; color: #856404;"></span></p>
								</div>
							</div>
						</div>

						<!-- Payment Section (if cost is set and unpaid) -->
						<div id="paymentSection" style="background: linear-gradient(135deg, #fff5e6 0%, #fffbf0 100%); border-radius: 12px; padding: 25px; margin-bottom: 40px; border: 2px solid #ffc107; display: none;">
							<div class="row align-items-center">
								<div class="col-md-8">
									<h5 style="color: #ff6b00; margin-bottom: 10px; margin-top: 0;">
										<i class="fa fa-credit-card mr-2"></i>Final Repair Cost
									</h5>
									<p style="color: #666; margin-bottom: 5px;">Your device repair estimate is ready for payment.</p>
									<p style="margin: 0; color: #333;">
										<strong style="font-size: 20px; color: #ff6b00;" id="paymentAmount">₦0.00</strong>
										<br>
										<small id="paymentNote" style="color: #666;"></small>
									</p>
								</div>
								<div class="col-md-4" style="text-align: right;">
									<button id="payNowBtn" onclick="proceedToPayment()" class="btn" style="background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); color: white; border: none; padding: 12px 30px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 16px;">
										<i class="fa fa-lock mr-2"></i>Pay Now
									</button>
									<br>
									<small style="color: #666; margin-top: 10px; display: block;">Secure payment processing</small>
								</div>
							</div>
						</div>
						<div class="progress-container">
							<p style="margin-bottom: 15px; font-weight: 600; color: #222;">Overall Progress</p>
							<div class="progress" style="height: 30px; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
								<div id="progressBar" class="progress-bar" style="width: 0%; background: linear-gradient(90deg, #28a745 0%, #20c997 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
									<span id="progressText">0%</span>
								</div>
							</div>
						</div>

						<!-- Timeline -->
						<div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
							<h5 style="margin-bottom: 30px; color: #222; font-weight: 700;">Repair Timeline</h5>
							<div class="timeline" id="repairTimeline">
								<!-- Timeline items will be inserted here -->
							</div>
						</div>

						<!-- Notes Section -->
						<div id="notesSection" style="margin-top: 30px; background: #f0f7ff; border-radius: 12px; padding: 25px; border-left: 5px solid #0066cc; display: none;">
							<h6 style="color: #222; font-weight: 600; margin-bottom: 15px;">Notes from Technician</h6>
							<p id="notesContent" style="color: #666; margin: 0; line-height: 1.6;"></p>
						</div>

						<!-- Back Button -->
						<div style="margin-top: 30px; text-align: center;">
							<button onclick="resetSearch()" class="btn" style="background: #f0f0f0; color: #222; border: none; padding: 10px 30px; border-radius: 8px; cursor: pointer; font-weight: 600;">
								<i class="fa fa-arrow-left mr-2"></i>Search Another Repair
							</button>
						</div>
					</div>

					<!-- No Results Message -->
					<div id="noResults" style="display: none; text-align: center; padding: 40px;">
						<i class="fa fa-search" style="font-size: 48px; color: #ccc; margin-bottom: 20px; display: block;"></i>
						<h4 style="color: #666; margin-bottom: 10px;">No Repair Found</h4>
						<p style="color: #999;">The tracking number you entered was not found. Please check and try again.</p>
					</div>

					<!-- Initial Message -->
					<div id="initialMessage" style="text-align: center; padding: 60px 20px;">
						<i class="fa fa-info-circle" style="font-size: 48px; color: #28a745; margin-bottom: 20px; display: block;"></i>
						<h5 style="color: #666; margin-bottom: 15px;">Enter your tracking number above to see the status of your device repair</h5>
						<p style="color: #999;">Your tracking number was provided in your booking confirmation email</p>
					</div>

				</div>
			</div>
		</div>
	</section>

	<!-- FOOTER -->
	<footer>
		@include('partials.footer-top')
		@include('partials.footer-bottom')
	</footer>

	<script src="{{ asset('buzbox/js/jquery-3.3.1.min.js') }}"></script>
	<script src="{{ asset('buzbox/js/bootstrap/popper.min.js') }}"></script>
	<script src="{{ asset('buzbox/js/bootstrap/bootstrap.min.js') }}"></script>

	<script>
	async function searchRepair() {
		const trackingNumber = document.getElementById('trackingInput').value.trim();

		if (!trackingNumber) {
			alert('Please enter a tracking number');
			return;
		}

		try {
			const response = await fetch(`/repairs/track/${trackingNumber}`);
			const data = await response.json();

			if (!response.ok || !data.success) {
				document.getElementById('initialMessage').style.display = 'none';
				document.getElementById('noResults').style.display = 'block';
				document.getElementById('repairDisplay').style.display = 'none';
				return;
			}

			displayRepairStatus(data);
			document.getElementById('initialMessage').style.display = 'none';
			document.getElementById('noResults').style.display = 'none';
			document.getElementById('repairDisplay').style.display = 'block';

		} catch (error) {
			console.error('Error fetching repair status:', error);
			alert('Error fetching repair status. Please try again.');
		}
	}

	function displayRepairStatus(data) {
		const repair = data.repair;
		const statuses = data.statuses;

		// Display repair info
		document.getElementById('trackingNum').textContent = repair.invoice_number;
		document.getElementById('deviceInfo').textContent = `${repair.device_brand} ${repair.device_model} (${repair.device_type})`;
		document.getElementById('repairStatus').textContent = repair.status;
		document.getElementById('customerName').textContent = repair.customer_name;
		document.getElementById('customerEmail').textContent = repair.customer_email;
		document.getElementById('customerPhone').textContent = repair.customer_phone;
		document.getElementById('urgencyLevel').textContent = repair.urgency;

		// Display repair cost and payment status
		if (repair.cost_actual && repair.cost_actual > 0) {
			document.getElementById('repairCost').textContent = '₦' + parseFloat(repair.cost_actual).toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
			
			// Show payment section if not paid
			if (repair.payment_status !== 'completed') {
				document.getElementById('paymentSection').style.display = 'block';
				document.getElementById('paymentAmount').textContent = '₦' + parseFloat(repair.cost_actual).toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
				document.getElementById('paymentNote').textContent = 'Click "Pay Now" to complete your payment';
				document.getElementById('paymentStatus').textContent = 'Pending Payment';
				document.getElementById('paymentStatus').style.background = '#f8d7da';
				document.getElementById('paymentStatus').style.color = '#721c24';
				
				// Store repair ID for payment
				window.currentRepairId = repair.id;
			} else {
				document.getElementById('paymentSection').style.display = 'none';
				document.getElementById('paymentStatus').textContent = 'Paid - Completed';
				document.getElementById('paymentStatus').style.background = '#d4edda';
				document.getElementById('paymentStatus').style.color = '#155724';
			}
		} else {
			document.getElementById('repairCost').textContent = 'Cost being calculated...';
			document.getElementById('paymentSection').style.display = 'none';
			document.getElementById('paymentStatus').textContent = 'Awaiting Cost Estimate';
			document.getElementById('paymentStatus').style.background = '#cfe2ff';
			document.getElementById('paymentStatus').style.color = '#084298';
		}

		// Update progress bar
		const progress = data.progress_percentage;
		document.getElementById('progressBar').style.width = progress + '%';
		document.getElementById('progressText').textContent = progress + '%';

		// Build timeline - Show latest updates at top
		const timeline = document.getElementById('repairTimeline');
		timeline.innerHTML = '';

		// Reverse the statuses array to show latest first
		const reversedStatuses = [...statuses].reverse();

		reversedStatuses.forEach((status, index) => {
			// In reversed array: index 0 is the latest (most recent)
			const isLatest = index === 0;
			const isCompleted = index > 0;
			const markerClass = isLatest ? 'active' : (isCompleted ? 'completed' : 'pending');

			let notesHtml = '';
			if (status.notes) {
				notesHtml = `<div style="background: #fff9e6; border-left: 3px solid #ffc107; padding: 10px; margin-top: 10px; border-radius: 4px; font-size: 13px;">
					<strong style="color: #ff9800;"><i class="fa fa-sticky-note mr-2"></i>Note:</strong> ${status.notes}
				</div>`;
			}

			const timelineHtml = `
				<div class="timeline-step">
					<div class="timeline-marker ${markerClass}">
						${isLatest ? '<i class="fa fa-star"></i>' : (isCompleted ? '<i class="fa fa-check"></i>' : (index + 1))}
					</div>
					<div class="timeline-content">
						<h5>${status.status}${isLatest ? ' <span style="background: #ffc107; color: #333; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 700;">LATEST</span>' : ''}</h5>
						<p>${status.description || 'Processing...'}</p>
						<small><strong>Updated:</strong> ${new Date(status.created_at).toLocaleString()}</small>
						${status.estimated_completion ? `<br><small><strong>Est. Completion:</strong> ${new Date(status.estimated_completion).toLocaleDateString()}</small>` : ''}
						${notesHtml}
					</div>
				</div>
			`;
			timeline.innerHTML += timelineHtml;
		});

		// Show notes if available
		if (repair.notes) {
			document.getElementById('notesSection').style.display = 'block';
			document.getElementById('notesContent').textContent = repair.notes;
		}
	}

	function resetSearch() {
		document.getElementById('trackingInput').value = '';
		document.getElementById('repairDisplay').style.display = 'none';
		document.getElementById('noResults').style.display = 'none';
		document.getElementById('initialMessage').style.display = 'block';
	}

	// Allow Enter key to search
	document.getElementById('trackingInput').addEventListener('keypress', function(e) {
		if (e.key === 'Enter') {
			searchRepair();
		}
	});

	function proceedToPayment() {
		if (window.currentRepairId) {
			window.location.href = `/repairs/${window.currentRepairId}/payment`;
		} else {
			alert('Unable to process payment. Please try again.');
		}
	}
	</script>
</body>
</html>
