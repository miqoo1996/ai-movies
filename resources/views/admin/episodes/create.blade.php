@extends('adminlte::page')

@section('title', 'Add Episode')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">Add Episode</h1>
            <small class="text-muted">{{ $show->getRawOriginal('title') }}</small>
        </div>
        <a href="{{ route('admin.shows.episodes.index', $show) }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
    </div>
@stop

@section('content')
<form method="POST" action="{{ route('admin.shows.episodes.store', $show) }}" enctype="multipart/form-data">
    @csrf
    @include('admin.episodes._form')
</form>
@stop

@section('js')
@include('admin.partials.ckeditor')
<script>
function previewThumb(input) {
    const label = input.nextElementSibling;
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
