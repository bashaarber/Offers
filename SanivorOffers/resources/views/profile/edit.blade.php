<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('public.profile_settings')</title>
</head>
<body>
    @include('layouts.sidebar')
    <div class="content">
        <div class="container py-4">
            <h2 class="mb-4" style="color: #333; font-weight: 600;">@lang('public.profile_settings')</h2>

            <div class="card mb-4" style="border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-radius: 12px;">
                <div class="card-body p-4">
                    <h5 style="font-weight: 600; margin-bottom: 4px;">@lang('public.profile_information')</h5>
                    <p class="text-muted mb-4" style="font-size: 14px;">@lang('public.profile_information_desc')</p>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card mb-4" style="border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-radius: 12px;">
                <div class="card-body p-4">
                    <h5 style="font-weight: 600; margin-bottom: 4px;">@lang('public.update_password')</h5>
                    <p class="text-muted mb-4" style="font-size: 14px;">@lang('public.update_password_desc')</p>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card mb-4" style="border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-radius: 12px;">
                <div class="card-body p-4">
                    <h5 style="font-weight: 600; margin-bottom: 4px;">@lang('public.language_preference')</h5>
                    <p class="text-muted mb-4" style="font-size: 14px;">@lang('public.language_preference_desc')</p>
                    @include('profile.partials.update-language-form')
                </div>
            </div>

            <div class="card mb-4" style="border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-radius: 12px; border-left: 4px solid #dc3545 !important;">
                <div class="card-body p-4">
                    <h5 style="font-weight: 600; margin-bottom: 4px; color: #dc3545;">@lang('public.delete_account')</h5>
                    <p class="text-muted mb-4" style="font-size: 14px;">@lang('public.delete_account_desc')</p>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</body>
</html>
