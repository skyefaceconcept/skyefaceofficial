@extends('layouts.admin.app')

@section('title', 'Page Details - ' )

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0 text-gray-800">
                <a href="{{ route('admin.analytics.page-impressions.index') }}" class="text-muted">
                    <i class="fas fa-arrow-left mr-2"></i>
                </a>
                <code class="text-primary">{{ $pageRoute }}</code>
            </h1>
            @if($pageStats)
                <small class="text-muted d-block mt-2">{{ $pageStats->page_title }}</small>
            @endif
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('admin.analytics.page-impressions.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Analytics
            </a>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" class="form-inline">
                <div class="form-group mr-3">
                    <label for="start_date" class="mr-2">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control"
                           value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="form-group mr-3">
                    <label for="end_date" class="mr-2">End Date:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control"
                           value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-primary text-uppercase mb-1">
                        <small class="font-weight-bold">Total Impressions</small>
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        {{ $pageStats ? number_format($pageStats->total) : 0 }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-success text-uppercase mb-1">
                        <small class="font-weight-bold">Unique Visitors</small>
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        {{ $pageStats ? number_format($pageStats->unique_visitors) : 0 }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-info text-uppercase mb-1">
                        <small class="font-weight-bold">Bounce Rate Est.</small>
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        @if($pageStats && $pageStats->unique_visitors > 0)
                            {{ round(($pageStats->unique_visitors / $pageStats->total) * 100, 2) }}%
                        @else
                            N/A
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Distribution Chart -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-line mr-2"></i>Daily Impressions
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="dailyChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Impressions Table -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-list mr-2"></i>Recent Impressions
                    </h6>
                </div>
                <div class="card-body">
                    @if($pageImpressions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>IP Address</th>
                                        <th>Referer</th>
                                        <th>User</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pageImpressions as $impression)
                                        <tr>
                                            <td>
                                                <small>{{ $impression->viewed_at->format('Y-m-d H:i:s') }}</small>
                                            </td>
                                            <td>
                                                <code class="text-primary" title="{{ $impression->ip_address }}">
                                                    {{ Str::limit($impression->ip_address, 20) }}
                                                </code>
                                            </td>
                                            <td>
                                                @if($impression->referer)
                                                    <a href="{{ $impression->referer }}" target="_blank" class="text-muted" title="{{ $impression->referer }}">
                                                        <small>{{ Str::limit($impression->referer, 40) }}</small>
                                                        <i class="fas fa-external-link-alt ml-1" style="font-size:0.8em;"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted"><small>Direct</small></span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $impression->user?->name ?? 'Guest' }}
                                            </td>
                                            <td class="text-right">
                                                @if($impression->user)
                                                    <a href="{{ route('admin.users.edit', $impression->user) }}"
                                                       class="btn btn-sm btn-outline-primary" title="View User">
                                                        <i class="fas fa-user"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $pageImpressions->links() }}
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle mr-2"></i>No impressions data available for the selected date range.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Daily Distribution Chart
    const dailyCtx = document.getElementById('dailyChart').getContext('2d');
    const dailyChart = new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailyStats->pluck('date')) !!},
            datasets: [
                {
                    label: 'Impressions',
                    data: {!! json_encode($dailyStats->pluck('impressions')) !!},
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y'
                },
                {
                    label: 'Unique Visitors',
                    data: {!! json_encode($dailyStats->pluck('visitors')) !!},
                    borderColor: '#1cc88a',
                    backgroundColor: 'rgba(28, 200, 138, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Impressions'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Visitors'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
</script>
@endsection
