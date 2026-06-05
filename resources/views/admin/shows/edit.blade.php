@extends('adminlte::page')

@section('title', 'Edit Show')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">Edit: {{ $show->getRawOriginal('title') }}</h1>
        <div class="d-flex" style="gap:.5rem;">
            <a href="{{ route('admin.shows.episodes.index', $show) }}" class="btn btn-success btn-sm">
                <i class="fas fa-list-ol mr-1"></i> Episodes
            </a>
            <a href="{{ route('shows.show', $show->slug) }}" target="_blank" class="btn btn-info btn-sm">
                <i class="fas fa-eye mr-1"></i> View
            </a>
            <a href="{{ route('admin.shows.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back
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

<form method="POST" action="{{ route('admin.shows.update', $show) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.shows._form')
</form>

{{-- ── Cast & Crew ──────────────────────────────────────────────── --}}
@php
    $castEntries = DB::table('show_person')
        ->join('people', 'people.id', '=', 'show_person.person_id')
        ->where('show_person.show_id', $show->id)
        ->where('show_person.department', 'cast')
        ->orderBy('show_person.sort_order')
        ->select('show_person.id as pivot_id', 'show_person.character_name', 'show_person.sort_order',
                 'people.id as person_id', 'people.name', 'people.photo', 'people.photo_local')
        ->get();

    // Resolve the best available photo URL for a raw DB entry
    $castPhotoUrl = fn($entry) => ($entry->photo_local && file_exists(storage_path('app/public/' . $entry->photo_local)))
        ? asset('storage/' . $entry->photo_local)
        : $entry->photo;
@endphp
<div class="card card-outline card-warning mt-2">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-users mr-2"></i>Cast & Crew</h3>
        <div class="card-tools">
            <span class="badge badge-warning">{{ $castEntries->count() }} members</span>
        </div>
    </div>
    <div class="card-body p-0">

        @if(session('cast_success'))
        <div class="alert alert-success alert-dismissible m-3 mb-0">
            {{ session('cast_success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
        @endif

        {{-- Existing cast table --}}
        @if($castEntries->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width:50px">#</th>
                        <th style="width:50px"></th>
                        <th>Actor / Actress</th>
                        <th>Character</th>
                        <th style="width:80px">Order</th>
                        <th style="width:100px"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($castEntries as $entry)
                @php $entryPhotoUrl = $castPhotoUrl($entry); @endphp
                <tr id="cast-row-{{ $entry->pivot_id }}">
                    {{-- View mode --}}
                    <td class="cast-view align-middle text-muted small">{{ $entry->sort_order }}</td>
                    <td class="cast-view align-middle">
                        @if($entryPhotoUrl)
                            <img src="{{ $entryPhotoUrl }}" style="width:36px;height:36px;object-fit:cover;border-radius:50%;">
                        @else
                            <div style="width:36px;height:36px;border-radius:50%;background:#dee2e6;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-user text-muted" style="font-size:14px;"></i>
                            </div>
                        @endif
                    </td>
                    <td class="cast-view align-middle font-weight-bold">{{ $entry->name }}</td>
                    <td class="cast-view align-middle text-muted">{{ $entry->character_name ?: '—' }}</td>
                    <td class="cast-view"></td>
                    <td class="cast-view align-middle text-right">
                        <button type="button" class="btn btn-xs btn-outline-primary" onclick="castEditToggle({{ $entry->pivot_id }})">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <form method="POST" action="{{ route('admin.shows.cast.destroy', [$show, $entry->pivot_id]) }}"
                              class="d-inline" onsubmit="return confirm('Remove {{ addslashes($entry->name) }} from cast?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-outline-danger"><i class="fas fa-times"></i></button>
                        </form>
                    </td>

                    {{-- Edit mode (hidden) --}}
                    <td colspan="6" class="cast-edit p-2" style="display:none;background:#fffbea;">
                        <form method="POST" action="{{ route('admin.shows.cast.update', [$show, $entry->pivot_id]) }}"
                              enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="row align-items-end" style="gap:0;">

                                <div class="col-sm-3 form-group mb-sm-0">
                                    <label class="small mb-1">Actor name</label>
                                    <input type="text" name="person_name" value="{{ $entry->name }}"
                                           class="form-control form-control-sm" required>
                                </div>

                                <div class="col-sm-2 form-group mb-sm-0">
                                    <label class="small mb-1">Character</label>
                                    <input type="text" name="character_name" value="{{ $entry->character_name }}"
                                           class="form-control form-control-sm" placeholder="Character name">
                                </div>

                                {{-- Photo upload --}}
                                <div class="col-sm-4 form-group mb-sm-0">
                                    <label class="small mb-1">
                                        Photo
                                        @if($entryPhotoUrl)
                                            <img src="{{ $entryPhotoUrl }}" id="cast-preview-{{ $entry->pivot_id }}"
                                                 style="width:22px;height:22px;border-radius:50%;object-fit:cover;vertical-align:middle;margin-left:4px;">
                                        @else
                                            <img id="cast-preview-{{ $entry->pivot_id }}" src="" style="width:22px;height:22px;border-radius:50%;object-fit:cover;vertical-align:middle;margin-left:4px;display:none;">
                                        @endif
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="person_photo_file"
                                                   id="cast-file-{{ $entry->pivot_id }}"
                                                   accept="image/*"
                                                   onchange="castPhotoPreview(this, {{ $entry->pivot_id }})">
                                            <label class="custom-file-label small" for="cast-file-{{ $entry->pivot_id }}">Upload…</label>
                                        </div>
                                    </div>
                                    <input type="text" name="person_photo" value="{{ $entry->photo }}"
                                           class="form-control form-control-sm mt-1" placeholder="or paste URL">
                                </div>

                                <div class="col-sm-1 form-group mb-sm-0">
                                    <label class="small mb-1">Order</label>
                                    <input type="number" name="sort_order" value="{{ $entry->sort_order }}"
                                           class="form-control form-control-sm" min="0">
                                </div>

                                <div class="col-sm-2 form-group mb-sm-0 d-flex" style="gap:4px;">
                                    <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-check"></i> Save</button>
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="castEditToggle({{ $entry->pivot_id }})"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="text-muted m-3">No cast members yet.</p>
        @endif

        {{-- Add new cast member --}}
        <div class="border-top p-3">
            <p class="font-weight-bold mb-2"><i class="fas fa-plus-circle mr-1 text-success"></i>Add Cast Member</p>
            <form method="POST" action="{{ route('admin.shows.cast.store', $show) }}" enctype="multipart/form-data">
                @csrf
                <div class="row" style="gap:0;">
                    {{-- Search / name --}}
                    <div class="col-sm-3 form-group">
                        <label class="small mb-1">Search existing person</label>
                        <input type="text" id="cast-search" autocomplete="off"
                               class="form-control form-control-sm" placeholder="Type a name…">
                        <input type="hidden" name="person_id" id="cast-person-id">
                        <div id="cast-search-results" class="list-group position-absolute" style="z-index:999;max-width:280px;display:none;"></div>
                    </div>
                    <div class="col-sm-3 form-group">
                        <label class="small mb-1">— or — New person name</label>
                        <input type="text" name="person_name" id="cast-new-name"
                               class="form-control form-control-sm" placeholder="Full name">
                    </div>
                    <div class="col-sm-2 form-group">
                        <label class="small mb-1">Character name</label>
                        <input type="text" name="character_name"
                               class="form-control form-control-sm" placeholder="Character name">
                    </div>

                    {{-- Photo --}}
                    <div class="col-sm-3 form-group">
                        <label class="small mb-1">
                            Photo
                            <img id="cast-add-preview" src="" style="width:22px;height:22px;border-radius:50%;object-fit:cover;vertical-align:middle;margin-left:4px;display:none;">
                        </label>
                        <div class="input-group input-group-sm mb-1">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="person_photo_file"
                                       id="cast-add-file" accept="image/*"
                                       onchange="castAddPhotoPreview(this)">
                                <label class="custom-file-label small" for="cast-add-file">Upload image…</label>
                            </div>
                        </div>
                        <input type="text" name="person_photo" id="cast-add-photo-url"
                               class="form-control form-control-sm" placeholder="or paste URL">
                    </div>

                    <div class="col-sm-1 form-group d-flex align-items-end">
                        <button type="submit" class="btn btn-sm btn-success btn-block">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── Gallery Images ──────────────────────────────────────────── --}}
<div class="card card-outline card-secondary mt-2">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-images mr-2"></i>Gallery Images</h3>
        <div class="card-tools">
            <span class="badge badge-info">{{ $images->count() }} images</span>
        </div>
    </div>
    <div class="card-body">

        {{-- Upload new images --}}
        <form method="POST" action="{{ route('admin.shows.update', $show) }}" enctype="multipart/form-data" id="gallery-form">
            @csrf @method('PUT')
            {{-- Pass the current title so validation passes --}}
            <input type="hidden" name="title" value="{{ $show->getRawOriginal('title') }}">
            <input type="hidden" name="slug"  value="{{ $show->slug }}">
            <div class="form-group">
                <label>Upload Images</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="gallery_files[]"
                               id="gallery_files" accept="image/*" multiple
                               onchange="updateGalleryLabel(this)">
                        <label class="custom-file-label" for="gallery_files">Choose images…</label>
                    </div>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-upload mr-1"></i> Upload
                        </button>
                    </div>
                </div>
                <small class="text-muted">JPG/PNG/WebP, max 4 MB each. Multiple files allowed.</small>
            </div>
            <div id="gallery-preview" class="d-flex flex-wrap mt-2" style="gap:6px;"></div>
        </form>

        {{-- Existing images grid --}}
        @if($images->isNotEmpty())
        <div class="row mt-3" style="gap:0;">
            @foreach($images as $img)
            @php
                $src = $img->image_url;
            @endphp
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                <div class="card h-100 shadow-sm" style="overflow:hidden;">
                    <img src="{{ $src }}" style="width:100%;height:120px;object-fit:cover;">
                    <div class="card-body p-1 text-center">
                        <a href="{{ $src }}" target="_blank" class="btn btn-xs btn-info mr-1" title="View full">
                            <i class="fas fa-expand"></i>
                        </a>
                        <form method="POST"
                              action="{{ route('admin.shows.images.destroy', [$show, $img]) }}"
                              class="d-inline"
                              onsubmit="return confirm('Delete this image?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
            <p class="text-muted mt-3 mb-0">No gallery images yet.</p>
        @endif

    </div>
</div>

@stop

@section('css')
<style>
.btn-xs { padding: 2px 7px; font-size: 11px; }
</style>
@stop

@section('js')
@include('admin.partials.ckeditor')
<script>
function previewPoster(input) {
    const label   = document.querySelector('label[for="' + input.id + '"]');
    const preview = document.getElementById('poster-preview');

    if (!input.files || !input.files[0]) return;

    const file = input.files[0];
    label.textContent = file.name;

    const reader = new FileReader();
    reader.onload = function (e) {
        if (preview) {
            preview.src = e.target.result;
        } else {
            const img  = document.createElement('img');
            img.id     = 'poster-preview';
            img.src    = e.target.result;
            img.style.cssText = 'width:80px;height:110px;object-fit:cover;border-radius:4px;';
            const wrap = document.createElement('div');
            wrap.className = 'col-auto';
            wrap.appendChild(img);
            input.closest('.row').insertAdjacentElement('afterbegin', wrap);
        }
    };
    reader.readAsDataURL(file);
}

// ── Cast photo previews ───────────────────────────────────────────
function castPhotoPreview(input, pivotId) {
    const label   = document.querySelector('label[for="cast-file-' + pivotId + '"]');
    const preview = document.getElementById('cast-preview-' + pivotId);
    if (!input.files || !input.files[0]) return;
    label.textContent = input.files[0].name;
    const reader = new FileReader();
    reader.onload = function(e) {
        preview.src = e.target.result;
        preview.style.display = '';
    };
    reader.readAsDataURL(input.files[0]);
}

function castAddPhotoPreview(input) {
    const label   = document.querySelector('label[for="cast-add-file"]');
    const preview = document.getElementById('cast-add-preview');
    if (!input.files || !input.files[0]) return;
    label.textContent = input.files[0].name;
    const reader = new FileReader();
    reader.onload = function(e) {
        preview.src = e.target.result;
        preview.style.display = '';
    };
    reader.readAsDataURL(input.files[0]);
}

// ── Cast inline edit toggle ───────────────────────────────────────
function castEditToggle(pivotId) {
    const row = document.getElementById('cast-row-' + pivotId);
    const views = row.querySelectorAll('.cast-view');
    const edit  = row.querySelector('.cast-edit');
    const isOpen = edit.style.display !== 'none';

    views.forEach(el => el.style.display = isOpen ? '' : 'none');
    edit.style.display = isOpen ? 'none' : '';
}

// ── Person search autocomplete ────────────────────────────────────
(function () {
    const searchInput  = document.getElementById('cast-search');
    const personIdInput = document.getElementById('cast-person-id');
    const newNameInput = document.getElementById('cast-new-name');
    const results      = document.getElementById('cast-search-results');
    let debounce;

    if (!searchInput) return;

    searchInput.addEventListener('input', function () {
        clearTimeout(debounce);
        const q = this.value.trim();
        personIdInput.value = '';

        if (q.length < 2) { results.style.display = 'none'; return; }

        debounce = setTimeout(function () {
            fetch('{{ route('admin.shows.cast.search', $show) }}?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(people => {
                    results.innerHTML = '';
                    if (!people.length) { results.style.display = 'none'; return; }

                    people.forEach(p => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'list-group-item list-group-item-action d-flex align-items-center py-1 px-2';
                        btn.style.fontSize = '13px';
                        btn.innerHTML = (p.photo
                            ? `<img src="${p.photo}" style="width:28px;height:28px;border-radius:50%;object-fit:cover;margin-right:8px;">`
                            : `<span style="width:28px;height:28px;border-radius:50%;background:#dee2e6;display:inline-flex;align-items:center;justify-content:center;margin-right:8px;font-size:11px;"><i class="fas fa-user"></i></span>`)
                            + `<span>${p.name}</span>`;

                        btn.addEventListener('click', function () {
                            personIdInput.value = p.id;
                            searchInput.value   = p.name;
                            newNameInput.value  = '';
                            results.style.display = 'none';
                        });
                        results.appendChild(btn);
                    });
                    results.style.display = 'block';
                });
        }, 250);
    });

    // Clear person_id when user types in new name field
    newNameInput.addEventListener('input', function () {
        if (this.value.trim()) {
            personIdInput.value = '';
            searchInput.value   = '';
        }
    });

    document.addEventListener('click', function (e) {
        if (!results.contains(e.target) && e.target !== searchInput) {
            results.style.display = 'none';
        }
    });
})();

function updateGalleryLabel(input) {
    const label   = document.querySelector('label[for="' + input.id + '"]');
    const preview = document.getElementById('gallery-preview');

    if (!input.files || !input.files.length) return;

    label.textContent = input.files.length > 1
        ? input.files.length + ' images selected'
        : input.files[0].name;

    // Thumbnail strip
    preview.innerHTML = '';
    Array.from(input.files).forEach(function (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.createElement('img');
            img.src   = e.target.result;
            img.style.cssText = 'width:60px;height:60px;object-fit:cover;border-radius:4px;border:2px solid #28a745;';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}
</script>
@stop
