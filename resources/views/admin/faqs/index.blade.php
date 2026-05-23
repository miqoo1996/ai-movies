@extends('adminlte::page')

@section('title', 'FAQs')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">FAQs</h1>
        <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Add FAQ
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

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">
            {{ $faqs->count() }} {{ Str::plural('question', $faqs->count()) }}
        </h3>
        <div class="card-tools">
            <small class="text-muted"><i class="fas fa-grip-vertical mr-1"></i> Drag rows to reorder</small>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th style="width:30px"></th>
                    <th style="width:30px">#</th>
                    <th>Question</th>
                    <th>Answer</th>
                    <th style="width:100px"></th>
                </tr>
            </thead>
            <tbody id="faq-sortable">
                @foreach($faqs as $faq)
                <tr data-id="{{ $faq->id }}" style="cursor:grab;">
                    <td class="align-middle text-muted" style="cursor:grab;">
                        <i class="fas fa-grip-vertical"></i>
                    </td>
                    <td class="align-middle text-muted small">{{ $loop->iteration }}</td>
                    <td class="align-middle font-weight-bold" style="max-width:320px;">
                        {{ Str::limit($faq->question, 80) }}
                    </td>
                    <td class="align-middle text-muted small" style="max-width:400px;">
                        {{ Str::limit(strip_tags($faq->answer), 100) }}
                    </td>
                    <td class="align-middle text-right">
                        <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-xs btn-warning" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" class="d-inline"
                              onsubmit="return confirm('Delete this FAQ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop

@section('css')
<style>
.btn-xs { padding: 2px 7px; font-size: 11px; }
#faq-sortable tr.sortable-chosen { background: #fff3cd; }
#faq-sortable tr.sortable-ghost  { opacity: .4; }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
Sortable.create(document.getElementById('faq-sortable'), {
    animation: 150,
    handle: '.fa-grip-vertical',
    ghostClass: 'sortable-ghost',
    chosenClass: 'sortable-chosen',
    onEnd: function () {
        var order = Array.from(document.querySelectorAll('#faq-sortable tr[data-id]'))
                        .map(function(tr) { return tr.dataset.id; });

        fetch('{{ route('admin.faqs.reorder') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ order: order })
        });
    }
});
</script>
@stop
