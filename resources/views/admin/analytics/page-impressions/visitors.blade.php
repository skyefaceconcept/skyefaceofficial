@extends('layouts.admin.app')

@section('title', 'Top Visitors')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-user-friends mr-2"></i>Top Visitors</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('admin.analytics.page-impressions.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-white">
            <h6 class="m-0 font-weight-bold">Visitors</h6>
        </div>
        <div class="card-body">
            @if(isset($topVisitors) && $topVisitors->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>IP Address</th>
                                <th>Device</th>
                                <th class="text-right">Impressions</th>
                                <th class="text-right">Last Seen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topVisitors as $v)
                                <tr>
                                    <td><code>{{ $v->ip_address }}</code></td>
                                    <td>{{ ucfirst($v->device_type) }}</td>
                                    <td class="text-right">{{ number_format($v->impressions ?? 0) }}</td>
                                    <td class="text-right">{{ optional($v->last_seen)->format('Y-m-d H:i') ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-muted">No visitor data available.</div>
            @endif
        </div>
    </div>
</div>

@endsection
