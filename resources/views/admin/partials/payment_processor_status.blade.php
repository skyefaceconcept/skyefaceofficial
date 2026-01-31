@php
use App\Services\PaymentProcessorService;

$processor = PaymentProcessorService::getActiveProcessor();
$isConfigured = PaymentProcessorService::isConfigured();
$isTestMode = PaymentProcessorService::isTestMode();
$currency = PaymentProcessorService::getCurrency();
$currencySymbol = PaymentProcessorService::getCurrencySymbol();
@endphp

<div class="payment-processor-info">
    <div class="alert {{ $isConfigured ? 'alert-success' : 'alert-warning' }} alert-dismissible fade show" role="alert">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="alert-heading mb-1">
                    💳 Payment Processor:
                    <strong>{{ ucfirst($processor) }}</strong>
                </h6>
                <small class="d-block">
                    @if($isConfigured)
                        ✅ Configured and Ready
                    @else
                        ⚠️ Not Configured - Please add your API keys
                    @endif

                    @if($isTestMode)
                        <span class="badge badge-warning">Test Mode Active</span>
                    @endif
                </small>
                <small class="d-block">
                    Currency: <strong>{{ $currency }} ({{ $currencySymbol }})</strong>
                </small>
            </div>
            <div>
                <a href="{{ route('admin.settings.payment_processors') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-cog"></i> Configure
                </a>
            </div>
        </div>


























</style>}    line-height: 1.6;.payment-processor-info small {}    font-size: 0.95rem;.payment-processor-info .alert-heading {}    margin-bottom: 0;.payment-processor-info .alert {}    margin-bottom: 1rem;.payment-processor-info {<style></div>    </div>        @endif            </p>                <strong>Next Step:</strong> Go to Settings → Payment Processors and add your API keys from Flutterwave or Paystack.            <p class="mb-0 mt-2">        @if(!$isConfigured)
