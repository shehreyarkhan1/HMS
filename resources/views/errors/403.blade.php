{{-- resources/views/errors/403.blade.php --}}
@extends('errors.layout')
@section('title', 'Access Denied')
@section('code', '403')
@section('icon') <i class="bi bi-shield-lock-fill text-danger"></i> @endsection
@section('heading', 'Security Restriction')
@section('message', $friendly_message ?? 'Access Denied. You do not have the necessary permissions to access this
    section or perform this action. If you believe this is an error, please contact your system administrator.')
