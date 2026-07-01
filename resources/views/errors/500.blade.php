@extends('errors.layout')
@section('title', 'System Error')
@section('code', '500')
@section('icon') <i class="bi bi-gear-wide-connected text-warning"></i> @endsection
@section('heading', 'System Maintenance')
@section('message', 'Database connection ya server mein koi masla aaya hai. Hamari team is par kaam kar rahi hai.')
