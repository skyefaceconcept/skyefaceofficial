@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 d-inline-block">Portfolio Management</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.portfolio.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Portfolio
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Footages</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($portfolios as $portfolio)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($portfolio->thumbnail)
                                        <img src="{{ asset('storage/' . $portfolio->thumbnail) }}" alt="{{ $portfolio->title }}"
                                             style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px; border-radius: 4px;">
                                    @else
                                        <div style="width: 40px; height: 40px; background: #ddd; margin-right: 10px; border-radius: 4px;"></div>
                                    @endif
                                    <strong>{{ $portfolio->title }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($portfolio->category) }}</span>
                            </td>
                            <td>
                                <strong>${{ number_format($portfolio->price, 2) }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-{{ $portfolio->status === 'published' ? 'success' : ($portfolio->status === 'draft' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($portfolio->status) }}
                                </span>
                            </td>
                            <td>{{ $portfolio->view_count }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $portfolio->footages()->count() }}</span>
                            </td>
                            <td>{{ $portfolio->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.portfolio.edit', $portfolio) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('shop.show', $portfolio) }}" class="btn btn-sm btn-info" target="_blank" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.portfolio.destroy', $portfolio) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this portfolio and all footages?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <p class="text-muted mb-0">No portfolios yet.</p>
                                <a href="{{ route('admin.portfolio.create') }}" class="btn btn-sm btn-primary mt-2">Create your first portfolio</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $portfolios->links() }}
    </div>
</div>
@endsection
