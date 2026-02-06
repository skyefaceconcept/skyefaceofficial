@extends('layouts.admin.app')

@section('title', 'Page Analytics - ' . $pageUrl)

@section('content')
<div class="container-fluid py-5">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2 font-weight-bold text-dark">Page Analytics</h1>
            <p class="text-muted break-word" style="word-break: break-all;">{{ $pageUrl }}</p>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('admin.analytics.page-impressions.index', ['days' => $days]) }}" 
               class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Key Metrics Row -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted small mb-2">Total Impressions</p>
                            <h3 class="text-primary font-weight-bold mb-0">{{ number_format($pageStats->total_impressions) }}</h3>
                        </div>
                        <div class="text-primary" style="font-size: 2.5rem; opacity: 0.1;">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted small mb-2">Unique Visitors</p>
                            <h3 class="text-success font-weight-bold mb-0">{{ number_format($pageStats->unique_visitors) }}</h3>
                        </div>
                        <div class="text-success" style="font-size: 2.5rem; opacity: 0.1;">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted small mb-2">Days Visited</p>
                            <h3 class="text-info font-weight-bold mb-0">{{ $pageStats->days_visited }}</h3>
                        </div>
                        <div class="text-info" style="font-size: 2.5rem; opacity: 0.1;">
                            <i class="fas fa-calendar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted small mb-2">Avg per Day</p>
                            <h3 class="text-warning font-weight-bold mb-0">{{ $pageStats->days_visited > 0 ? number_format(round($pageStats->total_impressions / $pageStats->days_visited)) : 0 }}</h3>
                        </div>
                        <div class="text-warning" style="font-size: 2.5rem; opacity: 0.1;">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 font-weight-bold">Daily Traffic</h5>
                </div>
                <div class="card-body">
                    <canvas id="dailyChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 font-weight-bold">Traffic by Device</h5>
                </div>
                <div class="card-body">
                    <canvas id="deviceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Referrers Section -->
    @if($referrers->count() > 0)
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 font-weight-bold">Top Referrers</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 py-3">Referrer</th>
                                        <th class="border-0 py-3 text-center">Visits</th>
                                        <th class="border-0 py-3 text-center">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalReferrerTraffic = $referrers->sum('total');
                                    @endphp
                                    @foreach($referrers as $referrer)
                                        <tr>
                                            <td class="py-3">
                                                <a href="{{ $referrer->referer }}" target="_blank" class="text-primary text-decoration-none">
                                                    {{ Str::limit($referrer->referer, 80) }}
                                                    <i class="fas fa-external-link-alt fa-xs"></i>
                                                </a>
                                            </td>
                                            <td class="py-3 text-center">
                                                <strong>{{ number_format($referrer->total) }}</strong>
                                            </td>
                                            <td class="py-3 text-center">
                                                <div class="progress" style="height: 24px;">
                                                    <div class="progress-bar bg-info" role="progressbar" 
                                                         style="width: {{ ($referrer->total / $totalReferrerTraffic) * 100 }}%" 
                                                         aria-valuenow="{{ $referrer->total }}" aria-valuemin="0" aria-valuemax="{{ $totalReferrerTraffic }}">
                                                        {{ round(($referrer->total / $totalReferrerTraffic) * 100) }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Views Table -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 font-weight-bold">Recent Views (Last 50)</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-3">Date & Time</th>
                                    <th class="border-0 py-3">IP Address</th>
                                    <th class="border-0 py-3">Device</th>
                                    <th class="border-0 py-3">Browser</th>
                                    <th class="border-0 py-3">OS</th>
                                    <th class="border-0 py-3">User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pageImpressions as $impression)
                                    <tr>
                                        <td class="py-3">
                                            <small>{{ $impression->viewed_at->format('Y-m-d H:i:s') }}</small>
                                        </td>
                                        <td class="py-3">
                                            <code class="small">{{ $impression->ip_address }}</code>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge badge-light text-dark">
                                                @if($impression->device_type === 'mobile')
                                                    <i class="fas fa-mobile-alt"></i>
                                                @elseif($impression->device_type === 'tablet')
                                                    <i class="fas fa-tablet-alt"></i>
                                                @else
                                                    <i class="fas fa-desktop"></i>
                                                @endif
                                                {{ ucfirst($impression->device_type) }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <small>{{ $impression->browser }}</small>
                                        </td>
                                        <td class="py-3">
                                            <small>{{ $impression->os }}</small>
                                        </td>
                                        <td class="py-3">
                                            @if($impression->user_id)
                                                <a href="#" class="text-decoration-none">
                                                    {{ $impression->user->name ?? 'User #' . $impression->user_id }}
                                                </a>
                                            @else
                                                <small class="text-muted">Anonymous</small>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            No views recorded for this page.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($pageImpressions->hasPages())
                        <div class="card-footer bg-white border-top">
                            {{ $pageImpressions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Daily Chart
    @php
        $dailyLabels = [];
        $dailyImpressions = [];
        $dailyVisitors = [];
        foreach($dailyStats as $stat) {
            $dailyLabels[] = $stat->date;
            $dailyImpressions[] = $stat->impressions;
            $dailyVisitors[] = $stat->unique_visitors;
        }
    @endphp

    const dailyCtx = document.getElementById('dailyChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailyLabels) !!},
            datasets: [
                {
                    label: 'Impressions',
                    data: {!! json_encode($dailyImpressions) !!},
                    borderColor: '#3182ce',
                    backgroundColor: 'rgba(49, 130, 206, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Unique Visitors',
                    data: {!! json_encode($dailyVisitors) !!},
                    borderColor: '#38a169',
                    backgroundColor: 'rgba(56, 161, 105, 0.1)',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Device Chart
    @php
        $deviceLabels = [];
        $deviceData = [];
        foreach($deviceStats as $stat) {
            $deviceLabels[] = ucfirst($stat->device_type);
            $deviceData[] = $stat->total;
        }
    @endphp

    const deviceCtx = document.getElementById('deviceChart').getContext('2d');
    new Chart(deviceCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($deviceLabels) !!},
            datasets: [{
                data: {!! json_encode($deviceData) !!},
                backgroundColor: [
                    '#3182ce',
                    '#38a169',
                    '#f6ad55'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>
@endpush
@endsection
