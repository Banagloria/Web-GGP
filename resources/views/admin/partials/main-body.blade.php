<main class="admin-main flex w-full max-w-full min-w-0 shrink-0 flex-col px-2.5 py-3 text-slate-200 max-md:pb-[max(0.75rem,env(safe-area-inset-bottom,0px))] sm:px-4 sm:py-4 md:p-6 lg:p-8">
    <article class="admin-main-body public-card-hover relative flex w-full max-w-full min-w-0 shrink-0 flex-col rounded-xl border border-white/10 bg-church-card ring-1 ring-church-gold/10 max-md:overflow-x-clip sm:rounded-2xl md:overflow-visible">
        <div class="pointer-events-none absolute -right-16 -top-16 hidden size-56 rounded-full bg-church-gold/12 blur-3xl md:block sm:size-64" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -bottom-12 left-1/4 hidden size-48 rounded-full bg-church-gold/10 blur-3xl md:block sm:size-56" aria-hidden="true"></div>
        <div class="pointer-events-none absolute right-1/3 top-1/2 hidden size-36 -translate-y-1/2 rounded-full bg-church-gold/8 blur-2xl md:block" aria-hidden="true"></div>

        <div class="relative z-[1] flex w-full min-w-0 flex-col">
            <div class="h-1 shrink-0 bg-gradient-to-r from-church-gold via-church-gold-soft to-church-gold" aria-hidden="true"></div>
            <div class="admin-main-body__content church-content-animate w-full max-w-full min-w-0 overflow-x-clip px-3 py-4 sm:px-4 sm:py-6 md:overflow-visible md:px-6 md:py-8 lg:px-8 lg:py-10">
                <x-flash-success />
                @yield('content')
            </div>
        </div>
    </article>
</main>
