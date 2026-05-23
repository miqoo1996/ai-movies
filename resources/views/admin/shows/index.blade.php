@extends('adminlte::page')

@section('title', 'Shows')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">Shows</h1>
        <a href="{{ route('admin.shows.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Add Show
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
        <form method="GET" class="d-flex" style="gap:.5rem;">
            <input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm" placeholder="Search by title…" style="max-width:300px;">
            <button class="btn btn-sm btn-default"><i class="fas fa-search"></i></button>
            @if($q)
                <a href="{{ route('admin.shows.index') }}" class="btn btn-sm btn-secondary">Clear</a>
            @endif
        </form>
        <div class="card-tools">
            <span class="badge badge-info">{{ $shows->total() }} shows</span>
        </div>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover table-sm mb-0">
            <thead class="thead-light">
                <tr>
                    <th style="width:60px">Poster</th>
                    <th>Title</th>
                    <th>Network</th>
                    <th>Status</th>
                    <th>Year</th>
                    <th>Episodes</th>
                    <th style="width:110px"></th>
                </tr>
            </thead>
            <tbody>
            @forelse($shows as $show)
                <tr>
                    <td class="align-middle">
                        @if($show->poster_local && file_exists(storage_path('app/public/' . $show->poster_local)))
                            <img src="{{ asset('storage/' . $show->poster_local) }}" style="width:36px;height:50px;object-fit:cover;border-radius:3px;">
                        @elseif($show->poster)
                            <img src="{{ $show->poster }}" style="width:36px;height:50px;object-fit:cover;border-radius:3px;">
                        @else
                            <div style="width:36px;height:50px;background:#e9ecef;border-radius:3px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-film text-muted" style="font-size:14px;"></i>
                            </div>
                        @endif
                    </td>
                    <td class="align-middle">
                        <a href="{{ route('admin.shows.edit', $show) }}" class="font-weight-bold text-dark">
                            {{ $show->getRawOriginal('title') }}
                        </a>
                        <div class="text-muted small">{{ $show->slug }}</div>
                    </td>
                    <td class="align-middle">{{ $show->network ?? '—' }}</td>
                    <td class="align-middle">
                        @php
                            $badge = match($show->status) {
                                'Running'          => 'success',
                                'Returning Series' => 'info',
                                'Ended'            => 'secondary',
                                'Cancelled'        => 'danger',
                                'Hiatus'           => 'warning',
                                default            => 'light',
                            };
                        @endphp
                        <span class="badge badge-{{ $badge }}">{{ $show->status ?? '—' }}</span>
                    </td>
                    <td class="align-middle">{{ $show->year ?? '—' }}</td>
                    <td class="align-middle">{{ $show->episodes_count }}</td>
                    <td class="align-middle text-right">
                        <a href="{{ route('admin.shows.edit', $show) }}" class="btn btn-xs btn-warning" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="/shows/{{ $show->slug }}" target="_blank" class="btn btn-xs btn-info" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.shows.destroy', $show) }}" class="d-inline"
                              onsubmit="return confirm('Delete {{ addslashes($show->getRawOriginal('title')) }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No shows found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($shows->hasPages())
    <div class="card-footer">
        {{ $shows->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>

@stop

@section('css')
<style>
.btn-xs { padding: 2px 7px; font-size: 11px; }
.pagination { margin: 0; }
.pagination .page-link { padding: 4px 10px; font-size: 13px; }
</style>
@stop
