@extends('layouts.admin.app')

@section('content')
<div class="container">
    <h1>Edit SEO (ID: {{ $seo->id }})</h1>

    <form method="POST" action="{{ route('admin.seo.update', $seo) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input name="title" class="form-control" value="{{ old('title', $seo->title) }}" />
        </div>

        <div class="mb-3">
            <label class="form-label">Meta description</label>
            <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description', $seo->meta_description) }}</textarea>
        </div>

        <button class="btn btn-primary">Save</button>
    </form>
</div>


@endsection
