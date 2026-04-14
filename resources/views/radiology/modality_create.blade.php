{{-- =====================================================
     resources/views/radiology/modalities/create.blade.php
     ===================================================== --}}
@extends('layouts.master')

@section('title', 'Add Modality')
@section('page-title', 'Radiology — Add Modality')
@section('breadcrumb', 'Home / Radiology / Modalities / Add')

@section('content')
    @include('radiology.modality_form', [
        'modality' => null,
        'route'    => route('radiology.modalities.store'),
        'method'   => 'POST',
        'btnLabel' => 'Create Modality',
    ])
@endsection