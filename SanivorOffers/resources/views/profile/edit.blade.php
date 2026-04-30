<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('public.profile_settings')</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    @include('layouts.sidebar')
    <div class="content">
        <div class="container py-4" style="max-width:760px;">
            <h2 class="mb-4" style="color:#1a1d23;font-weight:700;font-size:22px;">@lang('public.profile_settings')</h2>

            {{-- Profile Information --}}
            <div class="card mb-4" style="border:none;box-shadow:0 2px 12px rgba(0,0,0,0.08);border-radius:12px;">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h5 style="font-weight:600;margin-bottom:2px;font-size:16px;color:#111827;">@lang('public.profile_information')</h5>
                        <p class="text-muted mb-0" style="font-size:13px;">@lang('public.profile_information_desc')</p>
                    </div>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password --}}
            <div class="card mb-4" style="border:none;box-shadow:0 2px 12px rgba(0,0,0,0.08);border-radius:12px;">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h5 style="font-weight:600;margin-bottom:2px;font-size:16px;color:#111827;">@lang('public.update_password')</h5>
                        <p class="text-muted mb-0" style="font-size:13px;">@lang('public.update_password_desc')</p>
                    </div>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Language Preference --}}
            <div class="card mb-4" style="border:none;box-shadow:0 2px 12px rgba(0,0,0,0.08);border-radius:12px;">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h5 style="font-weight:600;margin-bottom:2px;font-size:16px;color:#111827;">@lang('public.language_preference')</h5>
                        <p class="text-muted mb-0" style="font-size:13px;">@lang('public.language_preference_desc')</p>
                    </div>
                    @include('profile.partials.update-language-form')
                </div>
            </div>
        </div>
    </div>
</body>
</html>
