@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p class="text-muted">Welcome back, <strong>{{ Auth::user()->name }}</strong>.</p>
@stop
