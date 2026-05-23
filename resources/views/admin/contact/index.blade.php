@extends('adminlte::page')

@section('title', 'Contact Messages')

@section('content_header')
    <h1 class="m-0">Contact Messages</h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="card card-outline card-primary">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width:16px;"></th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Received</th>
                    <th style="width:100px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($submissions as $sub)
                <tr class="{{ $sub->isUnread() ? 'font-weight-bold' : '' }}">
                    <td class="text-center pt-3">
                        @if($sub->isUnread())
                            <span class="badge badge-primary" title="Unread" style="width:10px;height:10px;border-radius:50%;display:inline-block;padding:0;"></span>
                        @endif
                    </td>
                    <td>{{ $sub->name }}</td>
                    <td>{{ $sub->email }}</td>
                    <td>{{ $sub->subject }}</td>
                    <td class="text-muted small">{{ $sub->created_at->format('d M Y, H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.contact.show', $sub) }}" class="btn btn-sm btn-outline-primary mr-1">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.contact.destroy', $sub) }}" class="d-inline"
                              onsubmit="return confirm('Delete this message?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No messages yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($submissions->hasPages())
    <div class="card-footer">
        {{ $submissions->links() }}
    </div>
    @endif
</div>

@stop
