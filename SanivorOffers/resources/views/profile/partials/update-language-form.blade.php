<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            @lang('public.language_preference')
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            @lang('public.language_preference_desc')
        </p>
    </header>

    <form method="GET" action="" class="mt-6" id="language-form">
        <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
            <a href="{{ url('/locale/de') }}"
               class="btn {{ app()->getLocale() === 'de' ? 'btn-primary' : 'btn-outline-secondary' }}"
               style="min-width:110px;border-radius:8px;">
                🇩🇪 @lang('public.lang_de')
            </a>
            <a href="{{ url('/locale/en') }}"
               class="btn {{ app()->getLocale() === 'en' ? 'btn-primary' : 'btn-outline-secondary' }}"
               style="min-width:110px;border-radius:8px;">
                🇬🇧 @lang('public.lang_en')
            </a>
        </div>

        @if (session('status') === 'language-updated')
            <p class="mt-3 text-sm" style="color:#16a34a;">@lang('public.save') ✓</p>
        @endif
    </form>
</section>
