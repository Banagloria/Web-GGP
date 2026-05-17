@props([
    'cms' => [],
    'detail',
    'iconPrefix' => 'form_jemaat',
    'formId' => null,
    'formAction',
    'submitIconKey',
    'consentId',
    'submitId',
])

@php($pk = 'pendaftaran')

<div class="reg-registration-page mx-auto min-w-0 max-w-6xl px-4 py-6 pb-[max(1.5rem,env(safe-area-inset-bottom))] sm:px-6 sm:py-10 lg:py-12">
    @include('partials.registration-page-header', [
        'cms' => $cms,
        'pageKey' => $pk,
        'title' => $detail['title'],
        'h1IconKey' => $iconPrefix.'_h1',
        'leafIconKey' => $iconPrefix.'_leaf',
        'leafLabel' => $detail['leaf_label'],
        'subtitle' => $detail['subtitle'],
    ])

    <div class="reg-registration-panels grid grid-cols-1 gap-8 lg:grid-cols-[minmax(0,1fr)_minmax(0,18rem)] lg:items-stretch lg:gap-8 xl:grid-cols-[minmax(0,1fr)_minmax(0,20rem)] xl:gap-10">
        <div class="reg-registration-panel-form min-w-0">
            <form
                @if ($formId) id="{{ $formId }}" @endif
                method="post"
                action="{{ $formAction }}"
                enctype="multipart/form-data"
                data-registration-consent
                class="reg-form-card"
            >
                @include('partials.registration-form-header', [
                    'icon' => $detail['form_header']['icon'] ?? 'fa-solid fa-pen-to-square',
                    'title' => $detail['form_header']['title'] ?? '',
                    'subtitle' => ($detail['form_header']['subtitle'] ?? '') !== '' ? $detail['form_header']['subtitle'] : null,
                ])

                <div class="reg-form-card-body">
                    @csrf

                    @foreach ($detail['sections'] ?? [] as $section)
                        @php($sectionId = 'reg-section-'.($section['key'] ?? 's'))
                        <section class="reg-form-section" aria-labelledby="{{ $sectionId }}">
                            <div class="reg-form-section-head">
                                <span class="reg-form-section-icon">
                                    <i class="{{ $section['icon'] ?? 'fa-solid fa-circle' }}" aria-hidden="true"></i>
                                </span>
                                <div id="{{ $sectionId }}">
                                    <h2 class="font-serif text-base font-semibold text-church-fg sm:text-lg">{{ $section['title'] ?? '' }}</h2>
                                    @if (! empty($section['subtitle']))
                                        <p class="text-xs text-slate-500">{{ $section['subtitle'] }}</p>
                                    @endif
                                </div>
                            </div>

                            @foreach ($section['groups'] ?? [] as $group)
                                @if (($group['layout'] ?? 'stack') === 'grid')
                                    <div class="grid gap-4 sm:grid-cols-2">
                                        @foreach ($group['fields'] ?? [] as $field)
                                            @include('partials.registration-field-from-cms', ['field' => $field])
                                        @endforeach
                                    </div>
                                @else
                                    @foreach ($group['fields'] ?? [] as $field)
                                        @include('partials.registration-field-from-cms', ['field' => $field])
                                    @endforeach
                                @endif
                            @endforeach
                        </section>
                    @endforeach

                    @include('partials.registration-consent-submit', [
                        'cms' => $cms,
                        'pageKey' => $pk,
                        'submitIconKey' => $submitIconKey,
                        'submitLabel' => $detail['consent']['submit_label'] ?? 'Kirim pendaftaran',
                        'consentText' => $detail['consent']['text'] ?? '',
                        'layout' => 'inline',
                        'consentId' => $consentId,
                        'submitId' => $submitId,
                    ])
                </div>
            </form>
        </div>

        @include('partials.registration-info-panel', [
            'title' => $detail['info_panel']['title'] ?? 'Alur pendaftaran',
            'subtitle' => $detail['info_panel']['subtitle'] ?? '',
            'panelIcon' => $detail['info_panel']['icon'] ?? 'fa-solid fa-route',
            'tipsHeading' => $detail['info_panel']['tips_heading'] ?? 'Tips',
            'tipsHeadingIcon' => $detail['info_panel']['tips_heading_icon'] ?? 'fa-solid fa-lightbulb',
            'steps' => $detail['info_panel']['steps'] ?? [],
            'tips' => $detail['info_panel']['tips'] ?? [],
        ])
    </div>
</div>
