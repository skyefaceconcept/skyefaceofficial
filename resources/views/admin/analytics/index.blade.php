@extends('layouts.admin.app')

@section('title', 'Page Impressions Analytics')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chart-line mr-2"></i>Page Impressions Analytics
            </h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('admin.analytics.export', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}"
               class="btn btn-sm btn-info">
                <i class="fas fa-download mr-2"></i>Export CSV
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

    <!-- Key Metrics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-primary text-uppercase mb-1">
                        <small class="font-weight-bold">Total Impressions</small>
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($totalImpressions) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-success text-uppercase mb-1">
                        <small class="font-weight-bold">Unique Visitors</small>
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($uniqueVisitors) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-info text-uppercase mb-1">
                        <small class="font-weight-bold">Pages Tracked</small>
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($uniquePages) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-warning text-uppercase mb-1">
                        <small class="font-weight-bold">Avg. Impressions/Day</small>
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        {{ $totalImpressions > 0 ? number_format(round($totalImpressions / max(1, $startDate->diffInDays($endDate) + 1))) : 0 }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-area mr-2"></i>Impressions & Visitors Over Time
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="impressionsChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-clock mr-2"></i>Hourly Distribution (Today)
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="hourlyChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Pages Table -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-list mr-2"></i>Top Pages by Impressions
                    </h6>
                </div>
                <div class="card-body">
                    @if($impressionsByPage->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="pl-3">Page Route</th>
                                        <th>Page Title</th>
                                        <th class="text-right">Impressions</th>
                                        <th class="text-right">Unique Visitors</th>
                                        <th class="text-right">Avg/Day</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($impressionsByPage as $page)
                                        <tr>
                                            <td class="pl-3">
                                                <code class="text-primary">{{ $page->page_route ?? 'unknown' }}</code>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ Str::limit($page->page_title, 50) }}</small>
                                            </td>
                                            <td class="text-right">
                                                <span class="badge badge-primary">{{ number_format($page->total_impressions) }}</span>
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($page->unique_visitors) }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_format(round($page->total_impressions / max(1, $startDate->diffInDays($endDate) + 1))) }}
                                            </td>
                                            <td class="text-center">
                                                                <a href="{{ route('admin.analytics.page-impressions.show', ['page' => $page->page_route, 'start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}"
                                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle mr-2"></i>No page impressions data available for the selected date range.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Top Referrers -->
    @if($topReferrers->count() > 0)
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-external-link-alt mr-2"></i>Top Referrers
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th>Referrer URL</th>
                                    <th class="text-right">Visits</th>
                                    <th class="text-right">Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topReferrers as $referrer)
                                    <tr>
                                        <td>
                                            <a href="{{ $referrer->referer }}" target="_blank" class="text-primary">
                                                {{ Str::limit($referrer->referer, 60) }}
                                                <i class="fas fa-external-link-alt ml-1" style="font-size:0.8em;"></i>
                                            </a>
                                        </td>
                                        <td class="text-right">
                                            <span class="badge badge-info">{{ number_format($referrer->count) }}</span>
                                        </td>
                                        <td class="text-right">
                                            {{ round(($referrer->count / $totalImpressions) * 100, 2) }}%
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
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Impressions Over Time Chart
    const impressionsCtx = document.getElementById('impressionsChart').getContext('2d');
    const impressionsChart = new Chart(impressionsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($impressionsByDate->pluck('date')) !!},
            datasets: [
                {
                    label: 'Impressions',
                    data: {!! json_encode($impressionsByDate->pluck('impressions')) !!},
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y'
                },
                {
                    label: 'Unique Visitors',
                    data: {!! json_encode($impressionsByDate->pluck('visitors')) !!},
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

    // Hourly Distribution Chart
    const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
    const hourlyChart = new Chart(hourlyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($hourlyData->pluck('hour')) !!},
            datasets: [{
                label: 'Views',
                data: {!! json_encode($hourlyData->pluck('count')) !!},
                backgroundColor: '#36b9cc',
                borderColor: '#2e8b9e',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
