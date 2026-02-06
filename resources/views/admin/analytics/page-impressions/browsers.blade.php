@extends('layouts.admin.app')

@section('title', 'Browser Analytics')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-globe mr-2"></i>Browser Analytics</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('admin.analytics.page-impressions.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h6 class="m-0 font-weight-bold">Top Browsers</h6>
        </div>
        <div class="card-body">
            @if(isset($browserStats) && $browserStats->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Browser</th>
                                <th class="text-right">Impressions</th>
                                <th class="text-right">Unique Visitors</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($browserStats as $b)
                                <tr>
                                    <td>{{ $b->browser }}</td>
                                    <td class="text-right">{{ number_format($b->impressions ?? $b->total ?? 0) }}</td>
                                    <td class="text-right">{{ number_format($b->unique_visitors ?? 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-muted">No browser data available.</div>
            @endif
        </div>
    </div>
</div>

@endsection
