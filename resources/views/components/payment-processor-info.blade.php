@php
    use App\Services\PaymentProcessorService;
    use App\Helpers\PaymentProcessorHelper;
    
    $processor = $processor ?? PaymentProcessorService::getActiveProcessor();
    $showLogo = $showLogo ?? true;
    $fullWidth = $fullWidth ?? false;
    $showConfig = $showConfig ?? false;
@endphp

<div class="payment-processor-info {{ $fullWidth ? 'w-100' : '' }}">
    <!-- Main Processor Display -->
    <div class="d-flex align-items-center justify-content-between p-3 border rounded bg-light mb-3">
        <div class="d-flex align-items-center">
            @if($showLogo)
                <div class="mr-3">
                    @if($processor === 'flutterwave')
                        <i class="fa fa-credit-card fa-2x" style="color: #F4545F;"></i>
                    @else
                        <i class="fa fa-money-check fa-2x" style="color: #00B140;"></i>
                    @endif
                </div>
            @endif
            <div>
                <h6 class="mb-1 font-weight-bold">{{ ucfirst($processor) }}</h6>
                <small class="text-muted">Active Payment Processor</small>
            </div>
        </div>
        <span class="badge badge-{{ PaymentProcessorService::getProcessorBadgeColor() }} px-3 py-2">
            <i class="fa fa-check-circle mr-1"></i>Active
        </span>
    </div>

    <!-- Processor Description -->
    @if($showConfig)
        <div class="alert alert-info alert-sm">
            <strong>{{ ucfirst($processor) }} Info:</strong><br>
            <small>{{ PaymentProcessorService::getProcessorDescription() }}</small>
        </div>
    @endif

    <!-- Supported Currencies -->
    <div class="mb-3">
        <label class="small font-weight-bold text-muted">Supported Currencies:</label>
        <div>
            @php
                $currencies = PaymentProcessorService::getSupportedCurrencies();
            @endphp
            @foreach($currencies as $currency)
                @php
                    $details = PaymentProcessorService::getCurrencyDetails($currency);
                @endphp
                <span class="badge badge-light mr-2 mb-2">
                    {{ $details['flag'] }} {{ $currency }}
                </span>
            @endforeach
        </div>
    </div>

    <!-- Configuration Status -->
    @php
        $status = PaymentProcessorHelper::getProcessorStatus();
    @endphp
    <div class="small">
        <span class="badge badge-{{ $status['status_class'] }}">
            {{ $status['status'] }}
        </span>
        @if($status['test_mode'])
            <span class="badge badge-warning ml-2">Test Mode</span>
        @endif
    </div>
</div>
