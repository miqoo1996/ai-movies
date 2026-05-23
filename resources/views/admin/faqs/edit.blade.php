@extends('adminlte::page')

@section('title', 'Edit FAQ')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">Edit FAQ</h1>
        <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back
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

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="card card-outline card-warning">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.faqs.update', $faq) }}" id="faq-form">
            @csrf @method('PUT')
            @include('admin.faqs._form')
            <button type="submit" class="btn btn-warning">
                <i class="fas fa-save mr-1"></i> Save Changes
            </button>
        </form>

        {{-- Delete is a separate form, NOT nested --}}
        <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}"
              class="d-inline ml-2" onsubmit="return confirm('Delete this FAQ?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-trash mr-1"></i> Delete
            </button>
        </form>
    </div>
</div>

@stop

@section('css')
<style>
.ck-editor__editable { min-height: 180px; }
</style>
@stop

@section('js')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
ClassicEditor.create(document.getElementById('faq-answer-editor'), {
    initialData: document.getElementById('faq-answer').value,
    toolbar: ['bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo'],
}).then(function(editor) {
    document.getElementById('faq-form').addEventListener('submit', function() {
        document.getElementById('faq-answer').value = editor.getData();
    });
}).catch(console.error);
</script>
@stop
