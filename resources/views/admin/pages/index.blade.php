@extends('adminlte::page')

@section('title', 'Pages')

@section('content_header')
    <h1 class="m-0">Static Pages</h1>
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
            <thead class="thead-light">
                <tr>
                    <th>Page</th>
                    <th>URL</th>
                    <th>Last updated</th>
                    <th style="width:80px"></th>
                </tr>
            </thead>
            <tbody>
            @foreach($pages as $page)
                <tr>
                    <td class="align-middle font-weight-bold">{{ $page->title }}</td>
                    <td class="align-middle">
                        <a href="{{ url($page->slug) }}" target="_blank" class="text-muted small">
                            /{{ $page->slug }}
                        </a>
                    </td>
                    <td class="align-middle text-muted small">
                        {{ $page->updated_at->diffForHumans() }}
                    </td>
                    <td class="align-middle text-right">
                        <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-xs btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop

@section('css')
<style>.btn-xs { padding: 2px 7px; font-size: 11px; }</style>
@stop
