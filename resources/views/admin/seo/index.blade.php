@extends('layouts.admin.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="mb-0">SEO Entries</h1>
            <small class="text-muted">Manage page titles and meta descriptions</small>
        </div>

        <div class="d-flex align-items-center">
            <form method="GET" class="mr-2" style="min-width:260px;">
                <div class="input-group">
                    <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search page, title or description...">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                    </div>
                </div>
            </form>

            <a href="{{ route('admin.seo.create') }}" class="btn btn-success">Create</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width:60px">#</th>
                            <th>Page</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th style="width:100px">Default</th>
                            <th style="width:160px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>
                                    @if($item->is_site_default)
                                        <span class="badge badge-info">Site default</span>
                                    @else
                                        <code>{{ $item->page_slug ?? '-' }}</code>
                                    @endif
                                </td>
                                <td style="max-width:300px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $item->title ?? '-' }}</td>
                                <td style="max-width:420px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ \Illuminate\Support\Str::limit($item->meta_description ?? '', 140) }}</td>
                                <td>
                                    @if($item->is_site_default)
                                        <span class="badge badge-success">Yes</span>
                                    @else
                                        <span class="text-muted">No</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <button class="btn btn-sm btn-outline-secondary mr-1" data-toggle="modal" data-target="#seoPreviewModal" data-title="{{ e($item->title) }}" data-description="{{ e($item->meta_description) }}" data-slug="{{ e($item->is_site_default ? 'Site default' : $item->page_slug) }}">Preview</button>
                                    <a href="{{ route('admin.seo.edit', $item) }}" class="btn btn-sm btn-primary mr-1">Edit</a>
                                    <button class="btn btn-sm btn-danger js-delete" data-action="{{ route('admin.seo.destroy', $item) }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">Showing {{ $items->count() }} of {{ $items->total() }} entries</small>
            <div>{{ $items->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="seoPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">SEO Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="mb-2 text-muted"><small>Slug: <span id="preview-slug"></span></small></div>
                <div class="border p-3 rounded">
                    <h5 id="preview-title" class="mb-1">Title Placeholder</h5>
                    <div class="text-primary mb-2">{{ url('/') }}</div>
                    <p id="preview-description" class="text-muted mb-0">Meta description placeholder</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this SEO entry?
            </div>
            <div class="modal-footer">
                <form id="delete-form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function(){
        // Preview modal population
        $('#seoPreviewModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            $('#preview-title').text(button.data('title') || '(no title)');
            $('#preview-description').text(button.data('description') || '(no description)');
            $('#preview-slug').text(button.data('slug') || '-');
        });

        // Delete button handler
        document.querySelectorAll('.js-delete').forEach(function(btn){
            btn.addEventListener('click', function(){
                var action = this.getAttribute('data-action');
                var form = document.getElementById('delete-form');
                form.setAttribute('action', action);
                $('#deleteConfirmModal').modal('show');
            });
        });
    });
</script>
@endpush

@endsection
