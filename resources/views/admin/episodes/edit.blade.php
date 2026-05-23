@extends('adminlte::page')

@section('title', 'Edit Episode')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">
                S{{ $episode->season_number }}E{{ $episode->episode_number }}
                @if($episode->title) — {{ $episode->title }} @endif
            </h1>
            <small class="text-muted">{{ $show->getRawOriginal('title') }}</small>
        </div>
        <a href="{{ route('admin.shows.episodes.index', $show) }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
    </div>
@stop

@section('content')
<form method="POST" action="{{ route('admin.shows.episodes.update', [$show, $episode]) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.episodes._form')
</form>

@section('js')
@include('admin.partials.ckeditor')
<script>
function previewThumb(input) {
    const label   = input.nextElementSibling;
    label.textContent = input.files[0]?.name ?? 'Choose image…';
    if (!input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        const img = document.getElementById('thumb-preview');
        img.src = e.target.result;
        img.style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@stop
@stop
