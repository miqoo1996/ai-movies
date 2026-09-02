@extends('adminlte::page')

@section('title', 'Articles')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">Articles</h1>
        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Add Article
        </a>
    </div>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $articles->total() }} {{ Str::plural('article', $articles->total()) }}</h3>
    </div>

    <div class="card-body border-bottom">
        <form method="GET" class="form-inline">
            <input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm mr-2"
                   placeholder="Search by title…" style="min-width:240px;">
            <select name="status" class="form-control form-control-sm mr-2">
                <option value="">All statuses</option>
                <option value="published" @selected($status === 'published')>Published</option>
                <option value="draft" @selected($status === 'draft')>Draft</option>
            </select>
            <button class="btn btn-sm btn-primary mr-2"><i class="fas fa-search mr-1"></i> Filter</button>
            @if($q || $status)
                <a href="{{ route('admin.articles.index') }}" class="btn btn-sm btn-secondary">Clear</a>
            @endif
        </form>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th style="width:70px"></th>
                    <th>Title</th>
                    <th style="width:140px">Published</th>
                    <th style="width:110px">Status</th>
                    <th style="width:120px"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                <tr>
                    <td class="align-middle">
                        @if($article->cover_url)
                            <img src="{{ $article->cover_url }}" alt=""
                                 style="width:56px;height:36px;object-fit:cover;border-radius:4px;">
                        @else
                            <div class="text-muted small text-center" style="width:56px;height:36px;line-height:36px;background:#f4f6f9;border-radius:4px;">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </td>
                    <td class="align-middle" style="max-width:420px;">
                        <div class="font-weight-bold">{{ Str::limit($article->title, 80) }}</div>
                        <small class="text-muted">/articles/{{ $article->slug }}</small>
                    </td>
                    <td class="align-middle text-muted small">
                        {{ $article->published_at?->format('M j, Y') ?? '—' }}
                    </td>
                    <td class="align-middle">
                        @if($article->is_published)
                            <span class="badge badge-success">Published</span>
                        @else
                            <span class="badge badge-secondary">Draft</span>
                        @endif
                    </td>
                    <td class="align-middle text-right">
                        <a href="{{ route('articles.show', $article) }}" target="_blank" class="btn btn-xs btn-info" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-xs btn-warning" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" class="d-inline"
                              onsubmit="return confirm('Delete this article?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No articles found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($articles->hasPages())
    <div class="card-footer">
        {{ $articles->links() }}
    </div>
    @endif
</div>

@stop

@section('css')
<style>
.btn-xs { padding: 2px 7px; font-size: 11px; }
</style>
@stop
