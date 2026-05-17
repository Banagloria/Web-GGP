@extends('layouts.public')

@php
    $cardKey = 'jemaat';
    $detail = \App\Support\PendaftaranCardCms::detailFromCms($cms, $cardKey);
@endphp

@section('title', $detail['title'])

@section('content')
    @include('partials.registration-detail-body', [
        'cms' => $cms,
        'detail' => $detail,
        'iconPrefix' => 'form_jemaat',
        'formId' => 'reg-jemaat-form',
        'formAction' => route('pendaftaran.jemaat.store'),
        'submitIconKey' => 'form_jemaat_submit',
        'consentId' => 'reg-jemaat-consent',
        'submitId' => 'reg-jemaat-submit',
    ])
@endsection
