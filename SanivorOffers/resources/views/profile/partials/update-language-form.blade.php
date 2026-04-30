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
    <p class="mt-3 mb-0" style="font-size:14px;color:#16a34a;"><i class="fas fa-check mr-1"></i>@lang('public.save') ✓</p>
@endif
