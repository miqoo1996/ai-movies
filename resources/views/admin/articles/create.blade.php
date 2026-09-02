@extends('adminlte::page')

@section('title', 'Add Article')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">Add Article</h1>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
    </div>
@stop

@section('content')

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data">
    @csrf
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
