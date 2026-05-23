@extends('adminlte::page')

@section('title', 'Edit – ' . $page->title)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">{{ $page->title }}</h1>
        <div class="d-flex" style="gap:.5rem;">
            <a href="{{ url($page->slug) }}" target="_blank" class="btn btn-info btn-sm">
                <i class="fas fa-eye mr-1"></i> View
            </a>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary btn-sm">
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

<div class="card card-outline card-primary">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.pages.update', $page) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <textarea name="content" class="ck-editor" rows="20">{{ old('content', $page->content) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Save
            </button>
        </form>
    </div>
</div>

@stop

@section('js')
@include('admin.partials.ckeditor')
@stop
