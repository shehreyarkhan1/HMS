@extends('errors.layout')
@section('title', 'System Error')
@section('code', '500')
@section('icon') <i class="bi bi-gear-wide-connected text-warning"></i> @endsection
@section('heading', 'System Maintenance')
@section('message', 'There is an issue with the database connection or the server. Our team is working to resolve it.')
