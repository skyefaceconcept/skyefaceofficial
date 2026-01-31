<!-- Device Repair Booking Modal -->
<div class="modal fade" id="repairBookingModal" tabindex="-1" role="dialog" aria-labelledby="repairBookingLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border: none; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">

            <!-- Modal Header -->
            <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); border: none; border-radius: 12px 12px 0 0;">
                <h5 class="modal-title" id="repairBookingLabel" style="color: white; font-weight: 700;">
                    <i class="fa fa-tools mr-2"></i>Quick Device Repair Booking
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body" style="padding: 30px;">

                <!-- Info Box -->
                <div style="background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(40, 167, 69, 0.02) 100%); border-radius: 12px; padding: 20px; margin-bottom: 30px; border-left: 5px solid #28a745;">
                    <h6 style="color: #28a745; font-weight: 700; margin-bottom: 10px;"><i class="fa fa-info-circle mr-2"></i>How It Works</h6>
                    <ul style="list-style: none; padding: 0; color: #555; line-height: 1.6; margin: 0; font-size: 13px;">
                        <li><i class="fa fa-check" style="color: #28a745; margin-right: 10px; font-weight: bold;"></i><strong>Fill Out Form:</strong> Tell us about your device</li>
                        <li><i class="fa fa-check" style="color: #28a745; margin-right: 10px; font-weight: bold;"></i><strong>Get Tracking:</strong> Instant confirmation</li>
                        <li><i class="fa fa-check" style="color: #28a745; margin-right: 10px; font-weight: bold;"></i><strong>Status: Pending:</strong> Awaiting device delivery</li>
                        <li><i class="fa fa-check" style="color: #28a745; margin-right: 10px; font-weight: bold;"></i><strong>Status: Received:</strong> Admin receives device & consultation fee charged</li>
                    </ul>
                </div>

                <!-- Booking Form -->
                <form id="repairBookingFormModal" onsubmit="submitRepairBooking(event)" style="margin-bottom: 0;">

                    <!-- Row 1: Name and Email -->
                    <div class="form-row" style="margin-bottom: 20px;">
                        <div class="form-group col-md-6">
                            <label for="repair_name_modal" style="font-weight: 600; color: #222; margin-bottom: 6px; font-size: 13px;">Full Name <span style="color: #dc3545;">*</span></label>
                            <input type="text" class="form-control" id="repair_name_modal" name="name" placeholder="John Doe" required style="border: 1px solid #ddd; border-radius: 6px; padding: 10px; font-size: 13px;">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="repair_email_modal" style="font-weight: 600; color: #222; margin-bottom: 6px; font-size: 13px;">Email <span style="color: #dc3545;">*</span></label>
                            <input type="email" class="form-control" id="repair_email_modal" name="email" placeholder="john@example.com" required style="border: 1px solid #ddd; border-radius: 6px; padding: 10px; font-size: 13px;">
                        </div>
                    </div>

                    <!-- Row 2: Phone and Device Type -->
                    <div class="form-row" style="margin-bottom: 20px;">
                        <div class="form-group col-md-6">
                            <label for="repair_phone_modal" style="font-weight: 600; color: #222; margin-bottom: 6px; font-size: 13px;">Phone <span style="color: #dc3545;">*</span></label>
                            <input type="tel" class="form-control" id="repair_phone_modal" name="phone" placeholder="+1 (555) 123-4567" required style="border: 1px solid #ddd; border-radius: 6px; padding: 10px; font-size: 13px;">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="repair_device_type_modal" style="font-weight: 600; color: #222; margin-bottom: 6px; font-size: 13px;">Device Type <span style="color: #dc3545;">*</span></label>
                            <select class="form-control" id="repair_device_type_modal" name="device_type" required style="border: 1px solid #ddd; border-radius: 6px; padding: 10px; font-size: 13px;">
                                <option value="">-- Select Device --</option>
                                <option value="Laptop">Laptop</option>
                                <option value="Desktop Computer">Desktop Computer</option>
                                <option value="Mobile Phone">Mobile Phone</option>
                                <option value="Tablet">Tablet</option>
                                <option value="Printer">Printer</option>
                                <option value="Other">Other Device</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 3: Device Brand and Model -->
                    <div class="form-row" style="margin-bottom: 20px;">
                        <div class="form-group col-md-6">
                            <label for="repair_brand_modal" style="font-weight: 600; color: #222; margin-bottom: 6px; font-size: 13px;">Brand <span style="color: #dc3545;">*</span></label>
                            <input type="text" class="form-control" id="repair_brand_modal" name="brand" placeholder="e.g., Apple, Dell" required style="border: 1px solid #ddd; border-radius: 6px; padding: 10px; font-size: 13px;">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="repair_model_modal" style="font-weight: 600; color: #222; margin-bottom: 6px; font-size: 13px;">Model <span style="color: #dc3545;">*</span></label>
                            <input type="text" class="form-control" id="repair_model_modal" name="model" placeholder="e.g., MacBook Pro" required style="border: 1px solid #ddd; border-radius: 6px; padding: 10px; font-size: 13px;">
                        </div>
                    </div>

                    <!-- Issue Description -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="repair_issue_modal" style="font-weight: 600; color: #222; margin-bottom: 6px; font-size: 13px;">Issue Description <span style="color: #dc3545;">*</span></label>
                        <textarea class="form-control" id="repair_issue_modal" name="issue_description" rows="3" placeholder="Describe the problem..." required style="border: 1px solid #ddd; border-radius: 6px; padding: 10px; font-size: 13px; resize: vertical;"></textarea>
                        <small style="color: #999;">Minimum 10 characters</small>
                    </div>

                    <!-- Urgency -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="repair_urgency_modal" style="font-weight: 600; color: #222; margin-bottom: 6px; font-size: 13px;">Urgency</label>
                        <select class="form-control" id="repair_urgency_modal" name="urgency" style="border: 1px solid #ddd; border-radius: 6px; padding: 10px; font-size: 13px;">
                            <option value="Normal">Normal (5-7 days)</option>
                            <option value="Express">Express (2-3 days)</option>
                            <option value="Urgent">Urgent (Next day)</option>
                        </select>
                    </div>

                    <!-- Estimated Consultation Fee Display -->
                    <div style="background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 193, 7, 0.05) 100%); border-radius: 8px; padding: 15px; margin-bottom: 20px; border-left: 4px solid #ffc107; display: none;" id="priceDisplayModal">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <p style="margin: 0; color: #666; font-size: 12px; font-weight: 500;">Estimated Consultation Fee</p>
                                <small style="color: #856404; font-weight: 600;">Will be charged when admin receives device</small>
                            </div>
                            <div style="text-align: right;">
                                <p style="margin: 0; color: #ffc107; font-size: 22px; font-weight: 700;" id="priceAmountModal">₦0.00</p>
                            </div>
                        </div>
                        <input type="hidden" id="repair_cost_estimate_modal" name="cost_estimate" value="0">
                    </div>

                    <!-- Message Display -->
                    <div id="repairMessageModal" style="margin-bottom: 20px; display: none;" class="alert"></div>

                </form>

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer" style="border-top: 1px solid #eee; background: #f8f9fa; border-radius: 0 0 12px 12px;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 6px;">Close</button>
                <button type="button" id="repairSubmitBtnModal" class="btn" style="background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); color: white; border: none; border-radius: 6px; padding: 8px 25px; font-weight: 600;" onclick="submitRepairBooking(event)">
                    <i class="fa fa-check mr-2"></i>Submit Booking
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    // Device repair pricing - will be loaded from API
    let repairPricingModal = {};
    let activePaymentProcessor = 'flutterwave'; // Default processor
    let currentRepairBooking = null; // Store booking data after creation

    // Fetch pricing from API on page load
    function loadRepairPricing() {
        fetch('<?php echo e(route("api.repairs.pricing")); ?>')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    repairPricingModal = data.pricing;
                    console.log('Repair pricing loaded:', repairPricingModal);
                }
            })
            .catch(error => console.error('Error loading pricing:', error));
    }

    // Load active payment processor
    function loadPaymentProcessor() {
        fetch('<?php echo e(route("api.payment.processor")); ?>')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    activePaymentProcessor = data.processor; // 'flutterwave' or 'paystack'
                    document.getElementById('processorName').textContent =
                        activePaymentProcessor === 'flutterwave' ? 'Flutterwave' : 'Paystack';
                    console.log('Active processor:', activePaymentProcessor);
                }
            })
            .catch(error => console.error('Error loading payment processor:', error));
    }

    // Update price when device type changes (modal version)
    function updateRepairPriceModal() {
        const deviceType = document.getElementById('repair_device_type_modal').value;
        const priceDisplay = document.getElementById('priceDisplayModal');
        const priceAmount = document.getElementById('priceAmountModal');
        const costInput = document.getElementById('repair_cost_estimate_modal');
        const processorInfo = document.getElementById('paymentProcessorInfo');

        console.log('Selected device type:', deviceType);
        console.log('Available pricing:', repairPricingModal);

        if (deviceType && repairPricingModal[deviceType] !== undefined) {
            const price = parseFloat(repairPricingModal[deviceType]);
            console.log('Price found:', price);
            priceAmount.textContent = '₦' + price.toFixed(2);
            costInput.value = price;
            priceDisplay.style.display = 'block';
            processorInfo.style.display = 'block';
        } else {
            console.log('No price found for device type:', deviceType);
            priceDisplay.style.display = 'none';
            processorInfo.style.display = 'none';
            costInput.value = '0';
        }
    }

    // Add event listener to device type dropdown (modal version)
    document.addEventListener('DOMContentLoaded', function() {
        // Load pricing and payment processor from API
        loadRepairPricing();
        loadPaymentProcessor();

        const deviceTypeSelectModal = document.getElementById('repair_device_type_modal');
        if (deviceTypeSelectModal) {
            deviceTypeSelectModal.addEventListener('change', updateRepairPriceModal);
        }

        // Handle modal close to reset form
        $('#repairBookingModal').on('hidden.bs.modal', function() {
            document.getElementById('repairBookingFormModal').reset();
            document.getElementById('priceDisplayModal').style.display = 'none';
            document.getElementById('repairMessageModal').style.display = 'none';
            document.getElementById('paymentProcessorInfo').style.display = 'none';
            currentRepairBooking = null;
        });

        // Also trigger price update when modal opens to show price if device already selected
        $('#repairBookingModal').on('show.bs.modal', function() {
            loadRepairPricing();
            loadPaymentProcessor();
            setTimeout(updateRepairPriceModal, 100);
        });
    });

    async function submitRepairBooking(event) {
        event.preventDefault();

        const form = document.getElementById('repairBookingFormModal');
        const messageDiv = document.getElementById('repairMessageModal');
        const submitButton = document.getElementById('repairSubmitBtnModal');
        const payButton = document.getElementById('repairPayBtnModal');

        const payload = {
            name: document.getElementById('repair_name_modal').value,
            email: document.getElementById('repair_email_modal').value,
            phone: document.getElementById('repair_phone_modal').value,
            device_type: document.getElementById('repair_device_type_modal').value,
            brand: document.getElementById('repair_brand_modal').value,
            model: document.getElementById('repair_model_modal').value,
            issue_description: document.getElementById('repair_issue_modal').value,
            urgency: document.getElementById('repair_urgency_modal').value,
            cost_estimate: parseFloat(document.getElementById('repair_cost_estimate_modal').value) || 0,
        };

        try {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i>Processing...';

            let csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : null;
            if (!csrfToken) {
                const tokenInput = document.querySelector('input[name="_token"]');
                csrfToken = tokenInput ? tokenInput.value : null;
            }

            const response = await fetch('<?php echo e(route("repairs.store")); ?>', {
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

            if (!response.ok) {
                let errHtml = '';
                if (data && data.errors) {
                    for (const key in data.errors) {
                        errHtml += `<div><i class="fa fa-exclamation-circle mr-2"></i>${data.errors[key].join('<br>')}</div>`;
                    }
                } else if (data && data.message) {
                    errHtml = `<div><i class="fa fa-exclamation-circle mr-2"></i>${data.message}</div>`;
                } else {
                    errHtml = '<div><i class="fa fa-exclamation-circle mr-2"></i>Unable to submit. Please try again.</div>';
                }
                messageDiv.className = 'alert alert-danger';
                messageDiv.innerHTML = errHtml;
                return;
            }

            if (data && data.success) {
                const trackingNumber = data.tracking_number || data.invoice_number;
                const repairId = data.repair_id;
                
                messageDiv.className = 'alert alert-success';
                
                // Create payment buttons HTML
                const paymentButtonsHTML = `
                    <div style="display: flex; gap: 8px; justify-content: center; margin-top: 15px; flex-wrap: wrap;">
                        <a href="/repairs/${repairId}/payment" class="btn btn-sm btn-success" style="padding: 10px 25px; font-weight: 600; border-radius: 6px; text-decoration: none; font-size: 13px; background: linear-gradient(135deg, #28a745 0%, #1fa935 100%) !important; color: white !important; border: none !important; display: inline-block !important; cursor: pointer;">
                            <i class="fa fa-credit-card mr-1"></i>Pay Now
                        </a>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal" style="padding: 10px 25px; font-weight: 600; border-radius: 6px; font-size: 13px; background: #f0f0f0 !important; color: #222 !important; border: 1px solid #ddd !important;">
                            <i class="fa fa-times mr-1"></i>Pay Later
                        </button>
                    </div>
                `;
                
                messageDiv.innerHTML = `
                    <div>
                        <div style="text-align: center; margin-bottom: 12px;">
                            <i class="fa fa-check-circle" style="color: #28a745; font-size: 32px;"></i>
                            <h5 style="color: #28a745; margin-top: 8px; margin-bottom: 2px;">Booking Confirmed!</h5>
                        </div>
                        <div style="background: rgba(40, 167, 69, 0.15); padding: 12px; border-radius: 6px; margin: 10px 0; border-left: 3px solid #28a745;">
                            <strong style="color: #28a745; font-size: 14px;">YOUR TRACKING NUMBER:</strong>
                            <div style="font-size: 18px; font-weight: 700; color: #28a745; font-family: 'Courier New', monospace; margin: 5px 0;">${trackingNumber}</div>
                            <small style="color: #666;">Save this number to track your repair</small>
                        </div>
                        <div style="background: rgba(13, 110, 253, 0.1); border: 1px solid #0d6efd; border-radius: 4px; padding: 10px; margin: 10px 0; font-size: 12px;">
                            <strong style="color: #0d6efd;"><i class="fa fa-info-circle mr-2"></i>Next Step:</strong>
                            <br><small style="color: #666;">Pay the diagnosis fee to confirm your booking, then bring your device to our office.</small>
                        </div>
                        <small style="color: #666;">✓ Confirmation email sent to ${data.repair.customer_email}</small>
                        ${paymentButtonsHTML}
                    </div>
                `;
                
                form.reset();
                document.getElementById('priceDisplayModal').style.display = 'none';
                
                // Hide submit button
                const submitBtn = submitButton;
                if (submitBtn) {
                    submitBtn.style.display = 'none';
                }
            } else {
                messageDiv.className = 'alert alert-danger';
                messageDiv.innerHTML = `<div><i class="fa fa-exclamation-circle mr-2"></i><strong>Error!</strong> ${data && data.message ? data.message : 'Unable to process booking.'}</div>`;
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fa fa-check mr-2"></i>Submit Booking';
            }
        } catch (error) {
            console.error('Error:', error);
            messageDiv.style.display = 'block';
            messageDiv.className = 'alert alert-danger';
            messageDiv.innerHTML = '<div><i class="fa fa-exclamation-circle mr-2"></i>Network error. Please try again.</div>';
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fa fa-check mr-2"></i>Submit Booking';
        }
    }
</script>
<?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/partials/repair-booking-modal.blade.php ENDPATH**/ ?>