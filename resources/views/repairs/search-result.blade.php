@extends('layouts.app')

@section('title', 'Repair Status - ' . $repair->invoice_number)

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('home') }}" class="btn btn-light" style="border: 1px solid #ddd;">
                    <i class="fa fa-arrow-left mr-2"></i>Back to Home
                </a>
            </div>

            <!-- Repair Details Card -->
            <div class="card mb-4" style="border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div class="card-header" style="background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); color: white; border-radius: 12px 12px 0 0; padding: 20px;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 style="margin: 0; font-weight: 700;">Repair Status</h4>
                            <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 14px;">Invoice: <strong>{{ $repair->invoice_number }}</strong></p>
                        </div>
                        <div class="col-md-4 text-md-right">
                            <div style="background: rgba(255,255,255,0.2); padding: 10px 15px; border-radius: 6px; display: inline-block;">
                                <small style="color: white;">Status</small>
                                <p style="margin: 5px 0 0 0; font-weight: 700;">{{ ucfirst($repair->status) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body" style="padding: 30px;">
                    <!-- Customer Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 style="color: #666; font-weight: 600; margin-bottom: 15px; text-transform: uppercase; font-size: 12px;">Customer Information</h6>
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                <li style="margin-bottom: 10px;">
                                    <strong style="color: #222;">Name:</strong> {{ $repair->customer_name }}
                                </li>
                                <li style="margin-bottom: 10px;">
                                    <strong style="color: #222;">Email:</strong> {{ $repair->customer_email }}
                                </li>
                                <li style="margin-bottom: 10px;">
                                    <strong style="color: #222;">Phone:</strong> {{ $repair->customer_phone }}
                                </li>
                            </ul>
                        </div>

                        <!-- Device Information -->
                        <div class="col-md-6">
                            <h6 style="color: #666; font-weight: 600; margin-bottom: 15px; text-transform: uppercase; font-size: 12px;">Device Information</h6>
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                <li style="margin-bottom: 10px;">
                                    <strong style="color: #222;">Type:</strong> {{ $repair->device_type }}
                                </li>
                                <li style="margin-bottom: 10px;">
                                    <strong style="color: #222;">Brand:</strong> {{ $repair->device_brand }}
                                </li>
                                <li style="margin-bottom: 10px;">
                                    <strong style="color: #222;">Model:</strong> {{ $repair->device_model }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <hr style="margin: 30px 0;">

                    <!-- Issue Description -->
                    <div class="mb-4">
                        <h6 style="color: #666; font-weight: 600; margin-bottom: 15px; text-transform: uppercase; font-size: 12px;">Issue Description</h6>
                        <p style="color: #555; line-height: 1.6; margin: 0;">{{ $repair->issue_description }}</p>
                    </div>

                    <hr style="margin: 30px 0;">

                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <h6 style="color: #666; font-weight: 600; margin-bottom: 15px; text-transform: uppercase; font-size: 12px;">Repair Progress</h6>
                        <div style="background: #f0f0f0; border-radius: 10px; height: 30px; overflow: hidden;">
                            <div style="background: linear-gradient(90deg, #28a745 0%, #1fa935 100%); height: 100%; width: {{ $progressPercentage }}%; transition: width 0.3s ease; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 12px;">
                                {{ $progressPercentage }}%
                            </div>
                        </div>
                    </div>

                    <!-- Cost Information -->
                    @if($repair->cost_estimate || $repair->cost_actual)
                        <hr style="margin: 30px 0;">
                        <div class="row">
                            @if($repair->cost_estimate)
                                <div class="col-md-6">
                                    <h6 style="color: #666; font-weight: 600; margin-bottom: 10px; text-transform: uppercase; font-size: 12px;">Estimated Cost</h6>
                                    <p style="font-size: 20px; color: #28a745; font-weight: 700; margin: 0;">₦{{ number_format($repair->cost_estimate, 2) }}</p>
                                </div>
                            @endif
                            @if($repair->cost_actual)
                                <div class="col-md-6">
                                    <h6 style="color: #666; font-weight: 600; margin-bottom: 10px; text-transform: uppercase; font-size: 12px;">Actual Cost</h6>
                                    <p style="font-size: 20px; color: #007bff; font-weight: 700; margin: 0;">₦{{ number_format($repair->cost_actual, 2) }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Repair History Timeline -->
            @if($statuses->count())
                <div class="card" style="border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div class="card-header" style="background: #f8f9fa; border-radius: 12px 12px 0 0; padding: 20px; border-bottom: 1px solid #eee;">
                        <h5 class="card-title" style="margin: 0; font-weight: 700;">Repair Timeline</h5>
                    </div>
                    <div class="card-body" style="padding: 30px;">
                        <div class="timeline" style="position: relative; padding-left: 40px;">
                            @foreach($statuses as $status)
                                <div style="display: flex; margin-bottom: 30px; position: relative;">
                                    <!-- Timeline dot -->
                                    <div style="position: absolute; left: -38px; top: 0; width: 20px; height: 20px; background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); border-radius: 50%; border: 3px solid white; box-shadow: 0 0 0 2px #28a745;"></div>

                                    <!-- Timeline content -->
                                    <div style="flex: 1;">
                                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                                            <h6 style="margin: 0; font-weight: 700; color: #222;">{{ $status->status }}</h6>
                                            <small style="color: #999; font-size: 12px;">{{ $status->created_at->format('M d, Y H:i') }}</small>
                                        </div>
                                        @if($status->notes)
                                            <p style="color: #666; margin: 10px 0 0 0; line-height: 1.6; padding: 10px; background: #f9f9f9; border-left: 3px solid #28a745; border-radius: 2px;">
                                                {{ $status->notes }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info" style="border-radius: 12px; border: 1px solid #d1ecf1;">
                    <i class="fa fa-info-circle mr-2"></i>
                    <strong>No updates yet.</strong> Your repair is in queue and will be processed soon.
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .timeline::before {
        content: '';
        position: absolute;
        left: -30px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(180deg, #28a745 0%, #1fa935 100%);
    }
</style>
@endsection
