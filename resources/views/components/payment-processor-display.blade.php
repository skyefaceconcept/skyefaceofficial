@php
    use App\Services\PaymentProcessorService;
    use App\Helpers\PaymentProcessorHelper;
    
    $processor = $processor ?? PaymentProcessorService::getActiveProcessor();
    $position = $position ?? 'top'; // top, sidebar, modal
    $showWarning = $showWarning ?? false;
@endphp

@if($position === 'sidebar')
    <!-- Sidebar Payment Processor Info -->
    <div class="card border-left-{{ PaymentProcessorService::getProcessorBadgeColor() }} shadow mb-4">
        <div class="card-body">
            <div class="text-{{ PaymentProcessorService::getProcessorBadgeColor() }} text-uppercase font-weight-bold text-xs mb-1">
                <i class="fa {{ PaymentProcessorService::getProcessorIcon() }}"></i> Processor
            </div>
            <div class="h6 mb-0 font-weight-bold text-gray-800">
                {{ ucfirst($processor) }}
            </div>
            @if(PaymentProcessorService::isTestMode())
                <span class="badge badge-warning badge-sm mt-2">Test Mode</span>
            @endif
        </div>
    </div>
@elseif($position === 'modal')
    <!-- Modal Payment Processor Info -->
    <div class="alert alert-info alert-icon alert-icon-sm fade show" role="alert">
        <div class="alert-icon">
            <i class="fa {{ PaymentProcessorService::getProcessorIcon() }}"></i>
        </div>
        <div class="alert-text">
            <strong>Processing with {{ ucfirst($processor) }}</strong><br>
            <small>{{ PaymentProcessorService::getProcessorDescription() }}</small>
        </div>
    </div>
@else
    <!-- Top/Main Payment Processor Info -->
    <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
        <i class="fa {{ PaymentProcessorService::getProcessorIcon() }} fa-lg mr-3 text-info"></i>
        <div>
            <h6 class="alert-heading mb-1">Payment Method</h6>
            <p class="mb-0">
                <strong>{{ ucfirst($processor) }}</strong>
                @if(PaymentProcessorHelper::hasMultipleProcessors())
                    <span class="badge badge-info ml-2">Multiple processors available</span>
                @endif
            </p>
        </div>
    </div>

    @if($showWarning && !PaymentProcessorService::isConfigured())
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-triangle mr-2"></i>
            <strong>Warning:</strong> The payment processor is not properly configured. 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endif
