@extends('layouts.public')

@php
    $cardKey = 'baptis';
    $detail = \App\Support\PendaftaranCardCms::detailFromCms($cms, $cardKey);
@endphp

@section('title', $detail['title'])

@section('content')
    @include('partials.registration-detail-body', [
        'cms' => $cms,
        'detail' => $detail,
        'iconPrefix' => 'form_baptism',
        'formAction' => route('pendaftaran.baptisan.store'),
        'submitIconKey' => 'form_baptism_submit',
        'consentId' => 'reg-baptism-consent',
        'submitId' => 'reg-baptism-submit',
    ])
@endsection
