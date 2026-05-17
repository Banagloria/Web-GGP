@extends('layouts.public')

@php
    $cardKey = 'nikah';
    $detail = \App\Support\PendaftaranCardCms::detailFromCms($cms, $cardKey);
@endphp

@section('title', $detail['title'])

@section('content')
    @include('partials.registration-detail-body', [
        'cms' => $cms,
        'detail' => $detail,
        'iconPrefix' => 'form_marriage',
        'formAction' => route('pendaftaran.pernikahan.store'),
        'submitIconKey' => 'form_marriage_submit',
        'consentId' => 'reg-marriage-consent',
        'submitId' => 'reg-marriage-submit',
    ])
@endsection
