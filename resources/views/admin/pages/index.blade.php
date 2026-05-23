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
                    <th>SEO Title</th>
                    <th>Meta Description</th>
                    <th style="width:70px" class="text-center">Noindex</th>
                    <th style="width:80px"></th>
                </tr>
            </thead>
            <tbody>
            @foreach($pages as $page)
                @php
                    $icon = match($page->slug) {
                        'home'     => 'fa-home text-violet',
                        'shows'    => 'fa-film text-purple',
                        'calendar' => 'fa-calendar-alt text-orange',
                        'faq'      => 'fa-question-circle text-info',
                        'contact'  => 'fa-envelope text-success',
                        'terms'    => 'fa-file-contract text-warning',
                        'privacy'  => 'fa-shield-alt text-primary',
                        default    => 'fa-file-alt text-secondary',
                    };
                @endphp
                <tr>
                    <td class="align-middle">
                        <i class="fas {{ $icon }} mr-2" style="font-size:16px;width:20px;text-align:center;"></i>
                        <strong>{{ $page->title }}</strong>
                        <br>
                        <a href="{{ url($page->slug) }}" target="_blank" class="text-muted small">/{{ $page->slug }}</a>
                    </td>
                    <td class="align-middle small">
                        @if($page->seo_title)
                            <span class="text-dark">{{ $page->seo_title }}</span>
                        @else
                            <span class="text-muted font-italic">Not set</span>
                        @endif
                    </td>
                    <td class="align-middle small">
                        @if($page->seo_description)
                            <span class="text-dark">{{ Str::limit($page->seo_description, 80) }}</span>
                        @else
                            <span class="text-muted font-italic">Not set</span>
                        @endif
                    </td>
                    <td class="align-middle text-center">
                        @if($page->noindex)
                            <span class="badge badge-danger">noindex</span>
                        @else
                            <span class="badge badge-success">index</span>
                        @endif
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
