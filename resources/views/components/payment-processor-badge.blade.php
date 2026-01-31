@php
    use App\Services\PaymentProcessorService;

    $processor = $processor ?? PaymentProcessorService::getActiveProcessor();
    $showDescription = $showDescription ?? false;
    $size = $size ?? 'md'; // sm, md, lg
@endphp

<div class="payment-processor-badge">
    @if($size === 'lg')
        <div class="card mb-3 border-{{ PaymentProcessorService::getProcessorBadgeColor() }}">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="fa {{ PaymentProcessorService::getProcessorIcon() }} fa-2x text-{{ PaymentProcessorService::getProcessorBadgeColor() }}"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">{{ ucfirst($processor) }}</h5>
                        @if($showDescription)
                            <p class="text-muted small mb-0">{{ PaymentProcessorService::getProcessorDescription() }}</p>
                        @else
                            <span class="badge badge-{{ PaymentProcessorService::getProcessorBadgeColor() }}">Active Payment Processor</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @elseif($size === 'sm')
        <span class="badge badge-{{ PaymentProcessorService::getProcessorBadgeColor() }}">
            <i class="fa {{ PaymentProcessorService::getProcessorIcon() }} mr-1"></i>{{ ucfirst($processor) }}
        </span>
    @else
        <div class="alert alert-info d-flex align-items-center" role="alert">
            <i class="fa {{ PaymentProcessorService::getProcessorIcon() }} fa-lg mr-3"></i>
            <div>
                <strong>Payment Processor:</strong> {{ ucfirst($processor) }}
                @if($showDescription)
                    <br><small>{{ PaymentProcessorService::getProcessorDescription() }}</small>
                @endif
            </div>
        </div>
    @endif
</div>
