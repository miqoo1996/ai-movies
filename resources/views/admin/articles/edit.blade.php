@extends('adminlte::page')

@section('title', 'Edit – ' . $article->title)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">{{ Str::limit($article->title, 60) }}</h1>
        <div class="d-flex" style="gap:.5rem;">
            <a href="{{ route('articles.show', $article) }}" target="_blank" class="btn btn-info btn-sm">
                <i class="fas fa-eye mr-1"></i> View
            </a>
            <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary btn-sm">
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

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.articles._form')
</form>

@stop

@section('css')
<style>
.ck-editor__editable { min-height: 400px; }
</style>
@stop

@section('js')
@include('admin.partials.ckeditor')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var input = document.getElementById('cover_file');
    if (input) {
        input.addEventListener('change', function () {
            var label = this.nextElementSibling;
            if (label && this.files.length) label.textContent = this.files[0].name;
        });
    }
});
</script>
@stop
