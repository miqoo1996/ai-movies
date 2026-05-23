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

{{-- ── Filter card ────────────────────────────────────────────────── --}}
<div class="card card-outline {{ $hasFilters ? 'card-warning' : 'card-secondary' }} mb-3">
    <div class="card-header" id="filter-heading" style="cursor:pointer;" onclick="toggleFilters()">
        <h3 class="card-title">
            <i class="fas fa-filter mr-2"></i> Filters
            @if($hasFilters)
                <span class="badge badge-warning ml-1">Active</span>
            @endif
        </h3>
        <div class="card-tools">
            @if($hasFilters)
                <a href="{{ route('admin.shows.index') }}" class="btn btn-sm btn-outline-secondary mr-1"
                   onclick="event.stopPropagation()">
                    <i class="fas fa-times mr-1"></i> Clear all
                </a>
            @endif
            <button type="button" class="btn btn-tool">
                <i id="filter-chevron" class="fas fa-chevron-{{ $hasFilters ? 'up' : 'down' }}"></i>
            </button>
        </div>
    </div>

    <div id="filter-body" style="{{ $hasFilters ? '' : 'display:none;' }}">
        <form method="GET" action="{{ route('admin.shows.index') }}" id="filter-form">
            <div class="card-body pb-2">
                <div class="row">

                    {{-- Search --}}
                    <div class="col-md-4 col-lg-3 form-group">
                        <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Search</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="q" value="{{ $q }}"
                                   class="form-control" placeholder="Title…">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-4 col-lg-2 form-group">
                        <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Status</label>
                        <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">All statuses</option>
                            @foreach($statuses as $s)
                                <option value="{{ $s }}" @selected($status === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Network --}}
                    <div class="col-md-4 col-lg-2 form-group">
                        <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Network</label>
                        <select name="network" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">All networks</option>
                            @foreach($networks as $n)
                                <option value="{{ $n }}" @selected($network === $n)>{{ $n }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Genre --}}
                    <div class="col-md-4 col-lg-2 form-group">
                        <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Genre</label>
                        <select name="genre" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">All genres</option>
                            @foreach($genres as $g)
                                <option value="{{ $g->id }}" @selected((int)$genre === $g->id)>{{ $g->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Year --}}
                    <div class="col-md-4 col-lg-1 form-group">
                        <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Year</label>
                        <select name="year" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">Any</option>
                            @foreach($years as $y)
                                <option value="{{ $y }}" @selected((string)$year === (string)$y)>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sort --}}
                    <div class="col-md-4 col-lg-2 form-group">
                        <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Sort by</label>
                        <select name="sort" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="id_desc"    @selected($sort==='id_desc')>Newest added</option>
                            <option value="title_asc"  @selected($sort==='title_asc')>Title A–Z</option>
                            <option value="year_desc"  @selected($sort==='year_desc')>Year (newest)</option>
                            <option value="year_asc"   @selected($sort==='year_asc')>Year (oldest)</option>
                            <option value="rating_desc"@selected($sort==='rating_desc')>Highest rated</option>
                            <option value="subs_desc"  @selected($sort==='subs_desc')>Most popular</option>
                        </select>
                    </div>

                    {{-- Has Poster --}}
                    <div class="col-md-4 col-lg-2 form-group">
                        <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Poster</label>
                        <select name="has_poster" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value=""  @selected($hasPoster==='')>Any</option>
                            <option value="1" @selected($hasPoster==='1')>Has poster</option>
                            <option value="0" @selected($hasPoster==='0')>No poster</option>
                        </select>
                    </div>

                    {{-- Has Gallery --}}
                    <div class="col-md-4 col-lg-2 form-group">
                        <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Gallery</label>
                        <select name="has_gallery" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value=""  @selected($hasGallery==='')>Any</option>
                            <option value="1" @selected($hasGallery==='1')>Has images</option>
                            <option value="0" @selected($hasGallery==='0')>No images</option>
                        </select>
                    </div>

                    {{-- Has Episodes --}}
                    <div class="col-md-4 col-lg-2 form-group">
                        <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Episodes</label>
                        <select name="has_episodes" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value=""  @selected($hasEpisodes==='')>Any</option>
                            <option value="1" @selected($hasEpisodes==='1')>Has episodes</option>
                            <option value="0" @selected($hasEpisodes==='0')>No episodes</option>
                        </select>
                    </div>

                </div>
            </div>
            <div class="card-footer py-2 d-flex align-items-center" style="gap:.5rem;">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-search mr-1"></i> Apply
                </button>
                <a href="{{ route('admin.shows.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                <span class="text-muted small ml-auto">
                    <strong>{{ $shows->total() }}</strong> show{{ $shows->total() === 1 ? '' : 's' }} found
                </span>
            </div>
        </form>
    </div>
</div>

{{-- ── Results table ──────────────────────────────────────────────── --}}
<div class="card card-outline card-primary">
    <div class="card-body p-0">
        <table class="table table-hover table-sm mb-0">
            <thead class="thead-light">
                <tr>
                    <th style="width:56px">Poster</th>
                    <th>Title</th>
                    <th>Network</th>
                    <th>Status</th>
                    <th>Year</th>
                    <th style="width:72px">Eps</th>
                    <th style="width:110px"></th>
                </tr>
            </thead>
            <tbody>
            @forelse($shows as $show)
                <tr>
                    <td class="align-middle">
                        @if($show->poster_url)
                            <img src="{{ $show->poster_url }}"
                                 style="width:36px;height:50px;object-fit:cover;border-radius:3px;">
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
                    <td class="align-middle text-center">{{ $show->episodes_count }}</td>
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
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="fas fa-search fa-2x text-muted mb-2 d-block"></i>
                        <span class="text-muted">No shows match your filters.</span>
                        <div class="mt-2">
                            <a href="{{ route('admin.shows.index') }}" class="btn btn-sm btn-secondary">Clear filters</a>
                        </div>
                    </td>
                </tr>
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
.btn-xs        { padding: 2px 7px; font-size: 11px; }
.pagination    { margin: 0; }
.pagination .page-link { padding: 4px 10px; font-size: 13px; }
.text-xs       { font-size: 0.7rem; }
</style>
@stop

@section('js')
<script>
var filterOpen = {{ $hasFilters ? 'true' : 'false' }};

function toggleFilters() {
    filterOpen = !filterOpen;
    document.getElementById('filter-body').style.display = filterOpen ? '' : 'none';
    document.getElementById('filter-chevron').className = 'fas fa-chevron-' + (filterOpen ? 'up' : 'down');
}
</script>
@stop
