@extends('adminlte::page')

@section('title', 'Admins')

@section('content_header')
    <h1 class="m-0">Admin Users</h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="row">

    {{-- ── Current admins ─────────────────────────────────────────── --}}
    <div class="col-lg-7">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ $users->count() }} {{ Str::plural('Admin', $users->count()) }}</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Added</th>
                            <th style="width:130px"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="align-middle font-weight-bold">
                                {{ $user->name }}
                                @if($user->id === auth()->id())
                                    <span class="badge badge-primary ml-1">You</span>
                                @endif
                            </td>
                            <td class="align-middle text-muted small">{{ $user->email }}</td>
                            <td class="align-middle text-muted small">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="align-middle text-right">
                                <button class="btn btn-xs btn-info"
                                        onclick="openReset({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                        title="Reset password">
                                    <i class="fas fa-key"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.admins.destroy', $user) }}"
                                      class="d-inline"
                                      onsubmit="return confirm('Remove {{ addslashes($user->name) }} as admin?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-danger" title="Remove">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ── Add admin ───────────────────────────────────────────────── --}}
    <div class="col-lg-5">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-plus mr-2"></i>Add Admin</h3>
            </div>
            <div class="card-body">

                @if($errors->has('name') || $errors->has('email') || $errors->has('password'))
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->only(['name','email','password']) as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.admins.store') }}">
                    @csrf
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Full name" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="email@example.com" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Min. 8 characters" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fas fa-plus mr-1"></i> Add Admin
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

{{-- ── Reset password modal ────────────────────────────────────────── --}}
<div class="modal fade" id="resetModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reset Password</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form method="POST" id="reset-form">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-3">Setting new password for <strong id="reset-name"></strong>.</p>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
                    </div>
                    <div class="form-group mb-0">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save mr-1"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

@section('css')
<style>.btn-xs { padding: 2px 7px; font-size: 11px; }</style>
@stop

@section('js')
<script>
function openReset(userId, name) {
    document.getElementById('reset-name').textContent = name;
    document.getElementById('reset-form').action =
        '{{ url("admin/admins") }}/' + userId + '/reset-password';
    $('#resetModal').modal('show');
}
</script>
@stop
