<!-- Repair Search Results Modal -->
<div class="modal fade" id="repairSearchResultsModal" tabindex="-1" role="dialog" aria-labelledby="repairSearchResultsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border: none; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            
            <!-- Modal Header -->
            <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); border: none; border-radius: 12px 12px 0 0; color: white;">
                <h5 class="modal-title" id="repairSearchResultsLabel" style="font-weight: 700;">
                    <i class="fa fa-tools mr-2"></i>Repair Status Information
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body" id="repairSearchResultsContent" style="padding: 30px;">
                <!-- Content will be loaded here -->
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer" style="border-top: 1px solid #eee; background: #f8f9fa; border-radius: 0 0 12px 12px;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 6px;">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchBtn = document.getElementById('topRepairSearchBtn');
    const searchForm = document.getElementById('topRepairSearchForm');
    const invoiceInput = document.getElementById('topRepairInvoiceInput');

    if (searchBtn) {
        searchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const invoiceNumber = invoiceInput.value.trim();

            if (!invoiceNumber) {
                alert('Please enter an invoice number');
                return;
            }

            // Show loading state
            searchBtn.disabled = true;
            searchBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

            // Make AJAX request
            fetch('<?php echo e(route("repairs.status")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    invoice_number: invoiceNumber
                })
            })
            .then(response => response.json())
            .then(data => {
                searchBtn.disabled = false;
                searchBtn.innerHTML = '<i class="fa fa-search"></i>';

                if (data.success) {
                    displayRepairInfo(data);
                    $('#repairSearchResultsModal').modal('show');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                searchBtn.disabled = false;
                searchBtn.innerHTML = '<i class="fa fa-search"></i>';
                alert('An error occurred. Please try again.');
            });
        });

        // Allow Enter key to search
        invoiceInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchBtn.click();
            }
        });
    }
});

function displayRepairInfo(data) {
    const repair = data.repair;
    const statuses = data.statuses;
    const progressPercentage = data.progressPercentage;

    let html = `
        <!-- Repair Details -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 style="color: #666; font-weight: 600; margin-bottom: 10px; text-transform: uppercase; font-size: 11px;">Invoice Number</h6>
                <p style="color: #222; font-size: 16px; font-weight: 600; margin: 0;">${repair.invoice_number}</p>
            </div>
            <div class="col-md-6">
                <h6 style="color: #666; font-weight: 600; margin-bottom: 10px; text-transform: uppercase; font-size: 11px;">Current Status</h6>
                <p style="color: #28a745; font-size: 16px; font-weight: 600; margin: 0;">${capitalizeFirst(repair.status)}</p>
            </div>
        </div>

        <hr style="margin: 20px 0;">

        <!-- Customer Info -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 style="color: #666; font-weight: 600; margin-bottom: 10px; text-transform: uppercase; font-size: 11px;">Customer Name</h6>
                <p style="color: #555; margin: 0;">${repair.customer_name}</p>
            </div>
            <div class="col-md-6">
                <h6 style="color: #666; font-weight: 600; margin-bottom: 10px; text-transform: uppercase; font-size: 11px;">Device Type</h6>
                <p style="color: #555; margin: 0;">${repair.device_type}</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <h6 style="color: #666; font-weight: 600; margin-bottom: 10px; text-transform: uppercase; font-size: 11px;">Brand</h6>
                <p style="color: #555; margin: 0;">${repair.device_brand}</p>
            </div>
            <div class="col-md-6">
                <h6 style="color: #666; font-weight: 600; margin-bottom: 10px; text-transform: uppercase; font-size: 11px;">Model</h6>
                <p style="color: #555; margin: 0;">${repair.device_model}</p>
            </div>
        </div>

        <hr style="margin: 20px 0;">

        <!-- Issue Description -->
        <div class="mb-4">
            <h6 style="color: #666; font-weight: 600; margin-bottom: 10px; text-transform: uppercase; font-size: 11px;">Issue Description</h6>
            <p style="color: #555; line-height: 1.6; margin: 0; font-size: 13px;">${repair.issue_description}</p>
        </div>

        <hr style="margin: 20px 0;">

        <!-- Progress Bar -->
        <div class="mb-4">
            <h6 style="color: #666; font-weight: 600; margin-bottom: 10px; text-transform: uppercase; font-size: 11px;">Repair Progress</h6>
            <div style="background: #f0f0f0; border-radius: 10px; height: 25px; overflow: hidden; margin-bottom: 5px;">
                <div style="background: linear-gradient(90deg, #28a745 0%, #1fa935 100%); height: 100%; width: ${progressPercentage}%; transition: width 0.3s ease; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 11px;">
                    ${progressPercentage}%
                </div>
            </div>
        </div>
    `;

    // Add cost info if available
    if (repair.cost_estimate || repair.cost_actual) {
        html += `<hr style="margin: 20px 0;">
        <div class="row">`;
        
        if (repair.cost_estimate) {
            html += `<div class="col-md-6">
                <h6 style="color: #666; font-weight: 600; margin-bottom: 5px; text-transform: uppercase; font-size: 11px;">Consultation Fee <span style="background: #ffc107; color: #333; padding: 2px 4px; border-radius: 2px; font-size: 9px;">(Paid - Non-Refundable)</span></h6>
                <p style="font-size: 16px; color: #28a745; font-weight: 700; margin: 0;">₦${formatNumber(parseFloat(repair.cost_estimate))}</p>
            </div>`;
        }

        if (repair.cost_actual) {
            html += `<div class="col-md-6">
                <h6 style="color: #666; font-weight: 600; margin-bottom: 5px; text-transform: uppercase; font-size: 11px;">Total Repair Cost</h6>
                <p style="font-size: 16px; color: #007bff; font-weight: 700; margin: 0;">₦${formatNumber(parseFloat(repair.cost_actual))}</p>
            </div>`;
        }

        html += `</div>`;
    }

    // Add timeline - Show latest updates at top
    if (statuses && statuses.length > 0) {
        html += `<hr style="margin: 20px 0;">
        <h6 style="color: #666; font-weight: 600; margin-bottom: 15px; text-transform: uppercase; font-size: 11px;">Status Timeline</h6>
        <div style="max-height: 300px; overflow-y: auto;">`;

        // Reverse the statuses array to show latest first
        const reversedStatuses = [...statuses].reverse();

        reversedStatuses.forEach((status, index) => {
            const statusDate = new Date(status.created_at);
            const dateStr = statusDate.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
            const isLatest = index === 0;
            
            html += `<div style="display: flex; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee; ${isLatest ? 'background: #f0f7f0; padding: 12px; margin: 0 -12px 15px -12px; border-radius: 6px;' : ''}">
                <div style="min-width: 20px; margin-right: 15px;">
                    <div style="width: 12px; height: 12px; background: ${isLatest ? '#ffc107' : '#28a745'}; border-radius: 50%; margin-top: 3px;"></div>
                </div>
                <div style="flex: 1;">
                    <p style="margin: 0; font-weight: 600; color: #222; font-size: 13px;">${status.status} ${isLatest ? '<span style="background: #ffc107; color: #333; padding: 2px 6px; border-radius: 3px; font-size: 10px; font-weight: 700; margin-left: 8px;">LATEST</span>' : ''}</p>
                    <small style="color: #999; font-size: 11px;">${dateStr}</small>`;
            
            if (status.notes) {
                html += `<p style="color: #666; margin: 8px 0 0 0; font-size: 12px; padding: 8px; background: #fff9e6; border-left: 3px solid #ffc107; border-radius: 2px;"><strong style="color: #ff9800;"><i class="fa fa-sticky-note mr-1"></i>Note:</strong> ${status.notes}</p>`;
            }
            
            html += `</div></div>`;
        });

        html += `</div>`;
    }

    document.getElementById('repairSearchResultsContent').innerHTML = html;
}

function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function formatNumber(num) {
    return num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}
</script>
<?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/partials/repair-search-modal.blade.php ENDPATH**/ ?>