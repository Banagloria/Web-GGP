@extends('layouts.public')

@section('title', 'Kontak')

@section('content')
    @php
        $pk = 'kontak';
        $formHeading = trim((string) ($cms['form_heading'] ?? ''));
        $formHint = trim((string) ($cms['form_hint'] ?? ''));
    @endphp
    <div class="reg-registration-page mx-auto min-w-0 max-w-6xl px-4 py-6 pb-[max(1.5rem,env(safe-area-inset-bottom))] sm:px-6 sm:py-10 lg:py-12">
        <header class="reg-page-header relative mb-8 sm:mb-10">
            <nav class="reg-page-breadcrumb relative z-[1] mb-5 flex flex-wrap items-center gap-x-2 gap-y-1 text-sm" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="inline-flex max-w-full items-center gap-1.5 font-medium text-church-gold underline-offset-4 transition hover:text-church-gold-soft hover:underline">
                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'breadcrumb_home', 'extraClasses' => 'text-xs opacity-90'])
                    {{ $cms['breadcrumb_home'] ?? 'Beranda' }}
                </a>
                @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'breadcrumb_sep', 'extraClasses' => 'text-[0.65rem] text-slate-600'])
                <span class="inline-flex max-w-full items-center gap-1.5 rounded-full bg-white/5 px-2.5 py-0.5 text-slate-200 ring-1 ring-white/10">
                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'breadcrumb_current', 'extraClasses' => 'text-xs text-church-gold/80'])
                    {{ $cms['breadcrumb_current'] ?? 'Kontak' }}
                </span>
            </nav>

            <div class="relative flex flex-wrap items-start gap-4 sm:items-center">
                <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-church-gold/25 to-church-gold/5 text-church-gold ring-1 ring-church-gold/30 sm:size-14">
                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'h1', 'extraClasses' => 'text-xl sm:text-2xl'])
                </span>
                <div class="min-w-0 flex-1">
                    <h1 class="font-serif text-2xl font-bold tracking-tight text-church-fg sm:text-3xl lg:text-[2rem]">{{ $cms['h1'] ?? 'Kontak' }}</h1>
                </div>
            </div>
        </header>

        <div class="reg-registration-panels grid min-w-0 grid-cols-1 gap-8">
            <div class="reg-registration-panel-form min-w-0">
                @if (session('status'))
                    <x-flash-success class="flex min-w-0 items-start gap-3 py-3.5">
                        @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'status_success', 'extraClasses' => 'mt-0.5 shrink-0 text-church-gold'])
                        <span class="min-w-0 flex-1 break-words">{{ session('status') }}</span>
                    </x-flash-success>
                @endif

                @error('form')
                    <div class="mb-6 flex min-w-0 items-start gap-3 rounded-xl border border-red-500/30 bg-red-950/40 px-4 py-3.5 text-sm text-red-200 ring-1 ring-red-500/20" role="alert">
                        <i class="fa-solid fa-triangle-exclamation mt-0.5 shrink-0 text-red-400" aria-hidden="true"></i>
                        <span class="min-w-0 flex-1 break-words">{{ $message }}</span>
                    </div>
                @enderror

                <form method="post" action="{{ route('kontak.store') }}" class="reg-form-card" aria-labelledby="kontak-form-heading">
                    @csrf
                    @include('partials.registration-form-header', [
                        'icon' => 'fa-solid fa-envelope',
                        'title' => $formHeading !== '' ? $formHeading : 'Kirim pesan',
                        'subtitle' => $formHint !== '' ? $formHint : null,
                    ])
                    <h2 id="kontak-form-heading" class="sr-only">{{ $formHeading !== '' ? $formHeading : 'Kirim pesan' }}</h2>

                    <div class="reg-form-card-body">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5">
                            @foreach ($cms['form_fields'] ?? [] as $field)
                                @php($fname = $field['name'] ?? '')
                                @if ($fname === '')
                                    @continue
                                @endif
                                @php($field['required'] = true)
                                @include('partials.contact-form-field', ['field' => $field])
                            @endforeach
                        </div>
                        <div class="mt-6">
                            <button
                                type="submit"
                                class="reg-submit-btn inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-church-gold to-church-gold-soft px-5 py-3.5 text-sm font-semibold text-church-navy focus-visible:ring-offset-2 focus-visible:ring-offset-church-card"
                            >
                                @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'submit', 'extraClasses' => ''])
                                {{ $cms['submit_label'] ?? 'Kirim pesan' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
