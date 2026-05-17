@extends('layouts.public')

@section('title', $detail['title'] ?? 'Pendaftaran')

@section('content')
    @include('partials.registration-detail-body', [
        'cms' => $cms,
        'detail' => $detail,
        'iconPrefix' => $iconPrefix,
        'formId' => 'reg-form-'.$slug,
        'formAction' => route('pendaftaran.store', $slug),
        'submitIconKey' => $iconPrefix.'_submit',
        'consentId' => 'reg-consent-'.$slug,
        'submitId' => 'reg-submit-'.$slug,
    ])
@endsection
