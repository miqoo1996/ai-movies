@extends('adminlte::page')

@section('title', 'Message from ' . $submission->name)

@section('content_header')
    <h1 class="m-0">Message from {{ $submission->name }}</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-8">
        <div class="card card-outline card-primary">
            <div class="card-body">
                <dl class="row mb-4">
                    <dt class="col-sm-2">From</dt>
                    <dd class="col-sm-10">{{ $submission->name }}</dd>

                    <dt class="col-sm-2">Email</dt>
                    <dd class="col-sm-10">
                        <a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a>
                    </dd>

                    <dt class="col-sm-2">Subject</dt>
                    <dd class="col-sm-10">{{ $submission->subject }}</dd>

                    <dt class="col-sm-2">Received</dt>
                    <dd class="col-sm-10 text-muted">{{ $submission->created_at->format('d M Y, H:i') }}</dd>

                    @if($submission->ip_address)
                    <dt class="col-sm-2">IP</dt>
                    <dd class="col-sm-10 text-muted small">{{ $submission->ip_address }}</dd>
                    @endif
                </dl>

                <hr>

                <div style="white-space:pre-wrap; font-size:0.9375rem; line-height:1.7;">{{ $submission->message }}</div>
            </div>
            <div class="card-footer d-flex gap-2">
                <a href="{{ route('admin.contact.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>
                <a href="mailto:{{ $submission->email }}" class="btn btn-primary">
                    <i class="fas fa-reply mr-1"></i> Reply by Email
                </a>
                <form method="POST" action="{{ route('admin.contact.destroy', $submission) }}"
                      class="ml-auto" onsubmit="return confirm('Delete this message?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger"><i class="fas fa-trash mr-1"></i> Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@stop
