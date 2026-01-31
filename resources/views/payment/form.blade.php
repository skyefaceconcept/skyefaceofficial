@php
    use App\Services\PaymentProcessorService;
    $activeProcessor = $processor ?? PaymentProcessorService::getActiveProcessor();
    $processorName = ucfirst($activeProcessor);
@endphp

@extends('layouts.app-buzbox')

@section('title', 'Payment - Quote #' . $quote->id)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fa fa-credit-card mr-2"></i>Complete Your Payment
        </h1>
        <div class="page-nav">
            <a href="{{ route('quotes.show', $quote->id) }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Quote
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content Area -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payment Details</h3>
                </div>
                <div class="card-block">
                    <!-- Quote Summary -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Quote Number</label>
                                <p class="form-control-plaintext"><strong>#{{ $quote->id }}</strong></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Status</label>
                                <p class="form-control-plaintext">
                                    <span class="badge badge-info">{{ ucfirst($quote->status ?? 'Quoted') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Service Package</label>
                                <p class="form-control-plaintext"><strong>{{ $quote->package ?? 'Professional Service' }}</strong></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Client Name</label>
                                <p class="form-control-plaintext"><strong>{{ $quote->name }}</strong></p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Currency Selection -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fa fa-globe mr-1"></i> Select Payment Currency
                        </label>
                        <select id="currency" class="form-control form-control-lg" onchange="updateAmount()">
                            <option value="NGN">🇳🇬 Nigerian Naira (₦)</option>
                            <option value="USD">🇺🇸 US Dollar ($)</option>
                            <option value="GHS">🇬🇭 Ghana Cedis (GH₵)</option>
                            <option value="KES">🇰🇪 Kenyan Shilling (KSh)</option>
                            <option value="UGX">🇺🇬 Ugandan Shilling (USh)</option>
                            <option value="ZAR">🇿🇦 South African Rand (R)</option>
                            <option value="RWF">🇷🇼 Rwanda Franc (FRw)</option>
                        </select>
                    </div>

                    <!-- Accepted Payment Methods -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fa fa-check-circle text-success mr-1"></i> Accepted Payment Methods
                        </label>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="p-3 rounded text-center" style="background: #f8f9fa; border: 1px solid #ddd;">
                                    <i class="fa fa-credit-card fa-2x text-primary mb-2"></i>
                                    <p class="small font-weight-bold mb-0">Card Payments</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded text-center" style="background: #f8f9fa; border: 1px solid #ddd;">
                                    <i class="fa fa-mobile fa-2x text-success mb-2"></i>
                                    <p class="small font-weight-bold mb-0">Mobile Money</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" id="openPaymentModalBtn" class="btn btn-primary btn-lg btn-block" style="font-weight: 600;">
                                <i class="fa fa-lock mr-2"></i>Pay Securely
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-secondary btn-lg btn-block" id="openStatusCheckBtn" style="font-weight: 600;">
                                <i class="fa fa-search mr-2"></i>Check Status
                            </button>
                        </div>
                    </div>

                    <!-- Security Info -->
                    <div class="alert alert-success mt-4 mb-0">
                        <i class="fa fa-shield mr-2"></i>
                        <strong>Your payment is 100% secure.</strong>
                        @php
                            use App\Services\PaymentProcessorService;
                            $processorName = ucfirst($activeProcessor);
                            $processorDesc = '';
                            if ($activeProcessor === 'paystack') {
                                $processorDesc = 'Paystack, a PCI DSS Level 1 compliant payment processor.';
                            } else {
                                $processorDesc = 'Flutterwave, a PCI DSS Level 1 compliant payment processor.';
                            }
                        @endphp
                        Powered by {{ $processorDesc }}
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-question-circle mr-2"></i>Frequently Asked Questions
                    </h3>
                </div>
                <div class="card-block">
                    <div class="accordion" id="faqAccordion">
                        <!-- FAQ Item 1 -->
                        <div class="mb-3">
                            <h6 class="mb-0">
                                <a class="btn btn-link btn-block text-left p-0" data-toggle="collapse" data-target="#faq1" style="text-decoration: none;">
                                    <i class="fa fa-chevron-right mr-2"></i> Is my payment secure?
                                </a>
                            </h6>
                            <div id="#faq1" class="collapse" data-parent="#faqAccordion">
                                <p class="text-muted small mt-2">
                                    Yes, your payment is 100% secure.
                                    @php
                                        $processorDesc = '';
                                        if ($activeProcessor === 'paystack') {
                                            $processorDesc = 'We use Paystack, a PCI DSS Level 1 compliant payment processor. Your card details are encrypted and never stored on our servers.';
                                        } else {
                                            $processorDesc = 'We use Flutterwave, a PCI DSS Level 1 compliant payment processor. Your card details are encrypted and never stored on our servers.';
                                        }
                                    @endphp
                                    {{ $processorDesc }}
                                </p>
                            </div>
                        </div>

                        <!-- FAQ Item 2 -->
                        <div class="mb-3">
                            <h6 class="mb-0">
                                <a class="btn btn-link btn-block text-left p-0" data-toggle="collapse" data-target="#faq2" style="text-decoration: none;">
                                    <i class="fa fa-chevron-right mr-2"></i> How long does payment take?
                                </a>
                            </h6>
                            <div id="#faq2" class="collapse" data-parent="#faqAccordion">
                                <p class="text-muted small mt-2">
                                    Most payments are processed instantly. You'll receive a confirmation email within minutes of successful payment.
                                </p>
                            </div>
                        </div>

                        <!-- FAQ Item 3 -->
                        <div>
                            <h6 class="mb-0">
                                <a class="btn btn-link btn-block text-left p-0" data-toggle="collapse" data-target="#faq3" style="text-decoration: none;">
                                    <i class="fa fa-chevron-right mr-2"></i> What if payment fails?
                                </a>
                            </h6>
                            <div id="#faq3" class="collapse" data-parent="#faqAccordion">
                                <p class="text-muted small mt-2">
                                    If your payment fails, you'll see an error message and your card won't be charged. Please try again or contact support.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Amount Due Card -->
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Amount Due</h3>
                </div>
                <div class="card-block text-center">
                    <div class="mb-3">
                        <p class="text-muted small">Total Amount</p>
                        <h2 class="text-primary mb-0">
                            <span id="currencySymbol">₦</span><span id="priceDisplay">{{ number_format($quote->quoted_price, 2) }}</span>
                        </h2>
                        <small class="text-muted">Including all applicable fees</small>
                    </div>
                </div>
            </div>

            <!-- Support Card -->
            <div class="card mt-4 bg-light">
                <div class="card-block text-center p-4">
                    <i class="fa fa-headphones fa-2x text-primary mb-3"></i>
                    <h6 class="card-title">Need Help?</h6>
                    <p class="text-muted small mb-3">If you have any questions about your payment, contact our support team.</p>
                    <a href="mailto:{{ config('company.support_email', 'support@skyeface.com') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-envelope mr-1"></i>Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fa fa-credit-card mr-2"></i>Complete Your Payment
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="text-center mb-4">
                    <h6 class="text-muted">Quote #{{ $quote->id }}</h6>
                    <h2 class="text-primary">
                        <span id="modalCurrencySymbol">₦</span><span id="modalPriceDisplay">{{ number_format($quote->quoted_price, 2) }}</span>
                    </h2>
                    <p class="text-muted small">Amount Due</p>
                </div>

                <!-- Currency Selection -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fa fa-globe mr-1"></i> Payment Currency
                    </label>
                    <select id="modalCurrency" class="form-control" onchange="updateModalAmount()">
                        <option value="NGN">🇳🇬 Nigerian Naira (₦)</option>
                        <option value="USD">🇺🇸 US Dollar ($)</option>
                        <option value="GHS">🇬🇭 Ghana Cedis (GH₵)</option>
                        <option value="KES">🇰🇪 Kenyan Shilling (KSh)</option>
                        <option value="UGX">🇺🇬 Ugandan Shilling (USh)</option>
                        <option value="ZAR">🇿🇦 South African Rand (R)</option>
                        <option value="RWF">🇷🇼 Rwanda Franc (FRw)</option>
                    </select>
                </div>

                <!-- Payment Processing Loader -->
                <div id="paymentLoader" class="text-center" style="display: none;">
                    <div class="spinner-border text-primary mb-3" role="status"></div>
                    <p class="text-muted">
                        <i class="fa fa-spinner fa-spin mr-1"></i> Initializing payment...
                    </p>
                </div>

                <!-- Payment Info -->
                <div id="paymentInfo" class="alert alert-info">
                    <i class="fa fa-info-circle mr-2"></i>
                    @php
                        if ($activeProcessor === 'paystack') {
                            echo 'Click the button below to proceed to our secure payment gateway powered by Paystack.';
                        } else {
                            echo 'Click the button below to proceed to our secure payment gateway powered by Flutterwave.';
                        }
                    @endphp
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times mr-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary btn-lg" id="proceedPaymentBtn" style="font-weight: 600;">
                    <i class="fa fa-lock mr-2"></i>Proceed to Payment
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Status Check Modal -->
<div class="modal fade" id="statusCheckModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fa fa-search mr-2"></i>Check Payment Status
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="text-muted mb-4">
                    If you received an error message but believe your payment may have gone through, click the button below to verify the actual status.
                </p>

                <!-- Loading State -->
                <div id="statusLoader" class="text-center" style="display: none;">
                    <div class="spinner-border text-primary mb-3" role="status"></div>
                    <p class="text-muted">
                        <i class="fa fa-spinner fa-spin mr-1"></i> Checking payment status...
                    </p>
                </div>

                <!-- Results State -->
                <div id="statusResult" style="display: none;">
                    <div id="statusAlert" class="alert" role="alert"></div>

                    <!-- Payment Details Card -->
                    <div id="statusDetails" style="display: none;" class="card mt-3">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0">Payment Details</h6>
                        </div>
                        <div class="card-block">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Status</dt>
                                <dd class="col-sm-8">
                                    <span id="detailStatus" class="badge badge-primary"></span>
                                </dd>

                                <dt class="col-sm-4">Amount</dt>
                                <dd class="col-sm-8">
                                    <strong id="detailAmount"></strong>
                                </dd>

                                <dt class="col-sm-4">Reference</dt>
                                <dd class="col-sm-8">
                                    <small id="detailReference" class="text-muted"></small>
                                </dd>

                                <dt class="col-sm-4">Date</dt>
                                <dd class="col-sm-8">
                                    <small id="detailDate" class="text-muted"></small>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times mr-1"></i>Close
                </button>
                <button type="button" class="btn btn-primary" id="checkStatusBtn">
                    <i class="fa fa-sync-alt mr-1"></i>Check Status
                </button>
            </div>
        </div>
    </div>
</div>

@if ($activeProcessor === 'paystack')
    <!-- Paystack payment script -->
    <script src="https://js.paystack.co/v1/inline.js"></script>
@else
    <!-- Flutterwave payment script -->
    <script src="https://checkout.flutterwave.com/v3.js"></script>
@endif

<script>
const PAYMENT_PROCESSOR = '{{ $activeProcessor }}';

document.addEventListener('DOMContentLoaded', function() {
    try {
        const currencySymbols = {
            'NGN': '₦',
            'USD': '$',
            'GHS': 'GH₵',
            'KES': 'KSh',
            'UGX': 'USh',
            'ZAR': 'R',
            'RWF': 'FRw'
        };

        const baseAmount = {{ $quote->quoted_price }};
        const currencySelect = document.getElementById('currency');
        const openPaymentModalBtn = document.getElementById('openPaymentModalBtn');
        const openStatusCheckBtn = document.getElementById('openStatusCheckBtn');
        const checkStatusBtn = document.getElementById('checkStatusBtn');

        // Initialize display
        if (currencySelect) {
            currencySelect.value = 'NGN';
            updateAmount();
        }

        // Update currency display
        function updateAmount() {
            const currency = currencySelect ? currencySelect.value : 'NGN';
            const symbol = currencySymbols[currency];
            const currencySymbolEl = document.getElementById('currencySymbol');
            const priceDisplayEl = document.getElementById('priceDisplay');

            if (currencySymbolEl && priceDisplayEl) {
                currencySymbolEl.textContent = symbol;
                priceDisplayEl.textContent = formatNumber(baseAmount);
            }
        }

        function formatNumber(num) {
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(num);
        }

        // Update currency on change
        if (currencySelect) {
            currencySelect.addEventListener('change', function() {
                updateAmount();
            });
        }

        // Handle Pay Securely button click - FETCH to API and REDIRECT to payment_link
        if (openPaymentModalBtn) {
            openPaymentModalBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const btn = this;
                const currency = currencySelect ? currencySelect.value : 'NGN';

                // Show loading state
                btn.disabled = true;
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i>Processing...';

                // Prepare payload
                const paymentData = {
                    amount: parseFloat(baseAmount),
                    currency: currency,
                    payment_method: PAYMENT_PROCESSOR
                };

                console.log('Sending payment request:', paymentData);

                // Get CSRF token safely
                const csrfTokenEl = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = csrfTokenEl ? csrfTokenEl.content : '';

                if (!csrfToken) {
                    btn.disabled = false;
                    btn.innerHTML = originalHTML;
                    alert('Security Error: CSRF token not found. Please refresh the page.');
                    console.error('CSRF token not found');
                    return;
                }

                // Send payment request
                fetch('{{ route("payment.create", $quote->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(paymentData)
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('API Response:', data);

                    if (data.success && data.payment_link) {
                        console.log('Payment link received:', data.payment_link);
                        // Redirect to payment processor
                        window.location.href = data.payment_link;
                    } else if (data.error) {
                        btn.disabled = false;
                        btn.innerHTML = originalHTML;
                        alert('Payment Error: ' + data.error);
                    } else {
                        btn.disabled = false;
                        btn.innerHTML = originalHTML;
                        alert('Unable to initialize payment. Please try again.');
                    }
                })
                .catch(error => {
                    btn.disabled = false;
                    btn.innerHTML = originalHTML;
                    console.error('Error:', error);
                    alert('Error: ' + error.message);
                });
            });
        }

        // Open Status Check Modal
        if (openStatusCheckBtn) {
            openStatusCheckBtn.addEventListener('click', function() {
                const statusCheckModal = document.getElementById('statusCheckModal');
                if (statusCheckModal) {
                    // Reset modal state
                    const statusLoader = document.getElementById('statusLoader');
                    const statusResult = document.getElementById('statusResult');
                    if (statusLoader) statusLoader.style.display = 'none';
                    if (statusResult) statusResult.style.display = 'none';

                    // Try different modal APIs
                    try {
                        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                            const modal = new bootstrap.Modal(statusCheckModal);
                            modal.show();
                        } else if (typeof jQuery !== 'undefined' && jQuery.fn.modal) {
                            jQuery(statusCheckModal).modal('show');
                        } else {
                            statusCheckModal.style.display = 'block';
                            statusCheckModal.classList.add('show');
                        }
                    } catch(e) {
                        console.warn('Modal library error, using fallback:', e);
                        statusCheckModal.style.display = 'block';
                        statusCheckModal.classList.add('show');
                    }
                }
            });
        }

        // Check Payment Status
        if (checkStatusBtn) {
            checkStatusBtn.addEventListener('click', function() {
                const quoteId = {{ $quote->id }};
                const btn = this;
                const loader = document.getElementById('statusLoader');
                const result = document.getElementById('statusResult');
                const alert = document.getElementById('statusAlert');
                const details = document.getElementById('statusDetails');

                if (loader) loader.style.display = 'block';
                if (result) result.style.display = 'none';
                btn.disabled = true;

                // Get CSRF token safely
                const csrfTokenEl = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = csrfTokenEl ? csrfTokenEl.content : '';

                fetch(`/payment/check-status/${quoteId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (loader) loader.style.display = 'none';
                    if (result) result.style.display = 'block';
                    btn.disabled = false;

                    if (alert) {
                        alert.className = 'alert';
                        if (data.status === 'completed') {
                            alert.classList.add('alert-success');
                            alert.innerHTML = '<i class="fa fa-check-circle mr-2"></i><strong>Success!</strong> ' + data.message;
                        } else if (data.status === 'pending') {
                            alert.classList.add('alert-warning');
                            alert.innerHTML = '<i class="fa fa-clock mr-2"></i><strong>Pending:</strong> ' + data.message;
                        } else if (data.status === 'failed') {
                            alert.classList.add('alert-danger');
                            alert.innerHTML = '<i class="fa fa-times-circle mr-2"></i><strong>Failed:</strong> ' + data.message;
                        } else {
                            alert.classList.add('alert-info');
                            alert.innerHTML = '<i class="fa fa-info-circle mr-2"></i>' + data.message;
                        }
                    }

                    if (data.payment && details) {
                        details.style.display = 'block';
                        const statusBadge = document.getElementById('detailStatus');
                        if (statusBadge) {
                            statusBadge.textContent = data.payment.status.toUpperCase();
                            statusBadge.className = 'badge';
                            if (data.payment.status === 'completed') {
                                statusBadge.classList.add('badge-success');
                            } else if (data.payment.status === 'pending') {
                                statusBadge.classList.add('badge-warning');
                            } else if (data.payment.status === 'failed') {
                                statusBadge.classList.add('badge-danger');
                            } else {
                                statusBadge.classList.add('badge-secondary');
                            }
                        }

                        const detailAmount = document.getElementById('detailAmount');
                        const detailReference = document.getElementById('detailReference');
                        const detailDate = document.getElementById('detailDate');

                        if (detailAmount) detailAmount.textContent = data.payment.currency + ' ' + parseFloat(data.payment.amount).toLocaleString('en-US', { minimumFractionDigits: 2 });
                        if (detailReference) detailReference.textContent = data.payment.reference;
                        if (detailDate) {
                            detailDate.textContent = data.payment.paid_at ? new Date(data.payment.paid_at).toLocaleString() : new Date(data.payment.created_at).toLocaleString();
                        }
                    }
                })
                .catch(error => {
                    if (loader) loader.style.display = 'none';
                    if (result) result.style.display = 'block';
                    btn.disabled = false;
                    if (alert) {
                        alert.className = 'alert alert-danger';
                        alert.innerHTML = '<i class="fa fa-exclamation-circle mr-2"></i><strong>Error:</strong> Unable to check payment status. Please try again.';
                    }
                    if (details) details.style.display = 'none';
                    console.error('Error checking payment status:', error);
                });
            });
        }

        // Accordion animation
        document.querySelectorAll('[data-toggle="collapse"]').forEach(btn => {
            btn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-chevron-right');
                    icon.classList.toggle('fa-chevron-down');
                }
            });
        });
    } catch(err) {
        console.error('Payment form error:', err);
    }
});
</script>

<style>
    .modal-header.bg-primary {
        background-color: #007bff !important;
    }

    .modal-header.bg-primary .close {
        color: white;
        opacity: 0.8;
    }

    .modal-header.bg-primary .close:hover {
        opacity: 1;
    }

    .accordion a.btn-link {
        text-decoration: none;
        color: #333;
        font-weight: 500;
    }

    .accordion a.btn-link:hover {
        color: #007bff;
        text-decoration: none;
    }

    #openPaymentModalBtn:hover,
    #openStatusCheckBtn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e3e3e3;
    }

    .page-header .page-title {
        margin: 0;
        font-size: 2rem;
        font-weight: 600;
        color: #333;
    }

    .page-nav {
        display: flex;
        gap: 0.5rem;
    }
</style>
@endsection
