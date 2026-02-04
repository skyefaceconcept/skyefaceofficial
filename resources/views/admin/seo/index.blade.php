@extends('layouts.admin.app')

@section('content')
<div class="container">
    <h1>SEO Entries</h1>

    <div class="mb-4">
        <h4>Static Pages</h4>
        <p>Quick links for common static pages. Click Edit to create or update SEO for a specific page.</p>
        @php
            $staticPages = ['home' => '/', 'about' => '/about', 'services' => '/services', 'contact' => '/contact', 'shop' => '/shop'];
        @endphp
        <div class="list-group mb-3">
            @foreach($staticPages as $slug => $path)
                <a href="{{ route('admin.seo.editPage', $slug) }}" class="list-group-item list-group-item-action">{{ ucfirst($slug) }} — {{ $path }}</a>
            @endforeach
        </div>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Page</th>
                <th>Title</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                @if($item->seoable_type === 'site')
                <td>Site default</td>
                @elseif($item->seoable_type === 'page' && $item->page_slug)
                <td>Page: {{ $item->page_slug }}</td>
                @else
                <td>{{ class_basename($item->seoable_type) . '#' . $item->seoable_id }}</td>
                @endif
                <td>{{ Str::limit($item->title, 60) }}</td>
                <td>{{ Str::limit($item->meta_description, 80) }}</td>
                <td><a href="{{ route('admin.seo.edit', $item) }}" class="btn btn-sm btn-primary">Edit</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $items->links() }}
</div>
@endsection
