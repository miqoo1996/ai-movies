@extends('adminlte::page')

@section('title', 'Episodes — ' . $show->getRawOriginal('title'))

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">Episodes</h1>
            <small class="text-muted">{{ $show->getRawOriginal('title') }}</small>
        </div>
        <div class="d-flex" style="gap:.5rem;">
            <a href="{{ route('admin.shows.episodes.create', $show) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Add Episode
            </a>
            <a href="{{ route('admin.shows.edit', $show) }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back to Show
            </a>
        </div>
    </div>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

@forelse($seasons as $seasonNumber => $episodes)
<div class="card card-outline card-primary mb-3">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">
            Season {{ $seasonNumber }}
        </h3>
        <div class="card-tools">
            <span class="badge badge-secondary">{{ $episodes->count() }} episodes</span>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-sm table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th style="width:50px">Ep</th>
                    <th>Title</th>
                    <th style="width:110px">Air Date</th>
                    <th style="width:80px">Aired</th>
                    <th style="width:80px">Finale</th>
                    <th style="width:90px"></th>
                </tr>
            </thead>
            <tbody>
            @foreach($episodes as $ep)
            <tr>
                <td class="align-middle text-center font-weight-bold">{{ $ep->episode_number }}</td>
                <td class="align-middle">
                    {{ $ep->title ?? '—' }}
                </td>
                <td class="align-middle">
                    {{ $ep->airs_on ? $ep->airs_on->format('d M Y') : '—' }}
                </td>
                <td class="align-middle text-center">
                    @if($ep->has_aired)
                        <span class="badge badge-success">Yes</span>
                    @else
                        <span class="badge badge-secondary">No</span>
                    @endif
                </td>
                <td class="align-middle text-center">
                    @if($ep->season_finale)
                        <span class="badge badge-warning">Finale</span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td class="align-middle text-right">
                    <a href="{{ route('admin.shows.episodes.edit', [$show, $ep]) }}"
                       class="btn btn-xs btn-warning" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.shows.episodes.destroy', [$show, $ep]) }}"
                          class="d-inline"
                          onsubmit="return confirm('Delete S{{ $ep->season_number }}E{{ $ep->episode_number }}?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-xs btn-danger" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@empty
    <div class="card"><div class="card-body text-muted">No episodes yet. <a href="{{ route('admin.shows.episodes.create', $show) }}">Add the first one.</a></div></div>
@endforelse

@stop

@section('css')
<style>.btn-xs { padding: 2px 7px; font-size: 11px; }</style>
@stop
