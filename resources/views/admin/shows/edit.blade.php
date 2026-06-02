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
                $src = ($img->local_path && file_exists(storage_path('app/public/'.$img->local_path)))
                    ? asset('storage/'.$img->local_path)
                    : $img->url;
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
