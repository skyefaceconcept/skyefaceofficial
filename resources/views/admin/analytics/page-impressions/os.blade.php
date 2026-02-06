@extends('layouts.admin.app')

@section('title', 'OS Analytics')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0 text-gray-800"><i class="fab fa-windows mr-2"></i>OS Analytics</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('admin.analytics.page-impressions.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-info text-white">
            <h6 class="m-0 font-weight-bold">Top Operating Systems</h6>
        </div>
        <div class="card-body">
            @if(isset($osStats) && $osStats->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Operating System</th>
                                <th class="text-right">Impressions</th>
                                <th class="text-right">Unique Visitors</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($osStats as $o)
                                <tr>
                                    <td>{{ $o->os }}</td>
                                    <td class="text-right">{{ number_format($o->impressions ?? $o->total ?? 0) }}</td>
                                    <td class="text-right">{{ number_format($o->unique_visitors ?? 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-muted">No OS data available.</div>
            @endif
        </div>
    </div>
</div>

@endsection
