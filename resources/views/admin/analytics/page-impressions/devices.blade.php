@extends('layouts.admin.app')

@section('title', 'Device Analytics')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-devices mr-2"></i>Device Analytics</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('admin.analytics.page-impressions.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Device Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="deviceChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h6 class="m-0 font-weight-bold">Breakdown</h6>
                </div>
                <div class="card-body">
                    @if(isset($deviceStats) && $deviceStats->count() > 0)
                        <ul class="list-group">
                            @foreach($deviceStats as $stat)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ ucfirst($stat->device_type) }}
                                    <span class="badge badge-primary">{{ number_format($stat->impressions ?? $stat->total ?? 0) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-muted">No device data available.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    @php
        $labels = isset($deviceStats) ? $deviceStats->pluck('device_type')->map(fn($d) => ucfirst($d))->toArray() : [];
        $data = isset($deviceStats) ? $deviceStats->pluck('impressions')->toArray() : (isset($deviceStats) ? $deviceStats->pluck('total')->toArray() : []);
    @endphp
    const ctx = document.getElementById('deviceChart');
    if (ctx) {
        new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{ data: {!! json_encode($data) !!}, backgroundColor: ['#4e73df','#1cc88a','#36b9cc','#f6c23e'] }]
            },
            options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'bottom' } } }
        });
    }
</script>
@endpush

@endsection
