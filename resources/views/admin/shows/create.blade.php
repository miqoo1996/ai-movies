@extends('adminlte::page')

@section('title', 'Add Show')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">Add Show</h1>
        <a href="{{ route('admin.shows.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
    </div>
@stop

@section('content')
<form method="POST" action="{{ route('admin.shows.store') }}" enctype="multipart/form-data">
    @csrf
    @include('admin.shows._form')
</form>
@stop

@section('js')
    @include('admin.partials.ckeditor')
@stop
