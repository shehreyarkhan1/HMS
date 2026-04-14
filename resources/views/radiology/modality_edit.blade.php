@extends('layouts.master')

@section('title', 'Edit Modality — ' . $modality->name)
@section('page-title', 'Radiology — Edit Modality')
@section('breadcrumb', 'Home / Radiology / Modalities / Edit')

@section('content')
    @include('radiology.modality_form', [
        'modality' => $modality,
        'route'    => route('radiology.modalities.update', $modality->id),
        'method'   => 'PUT',
        'btnLabel' => 'Save Changes',
    ])
@endsection