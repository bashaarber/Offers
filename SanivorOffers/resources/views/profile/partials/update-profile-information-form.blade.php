<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="form-group row align-items-center mb-3">
        <label class="col-sm-3 col-form-label" style="font-weight:500;font-size:14px;color:#374151;">@lang('public.username_label')</label>
        <div class="col-sm-9">
            <div style="display:flex;align-items:center;gap:8px;">
                <span style="display:inline-block;background:#f3f4f6;border:1.5px solid #e5e7eb;border-radius:6px;padding:6px 12px;font-size:14px;color:#6b7280;min-width:180px;">{{ $user->username }}</span>
                <span style="font-size:12px;color:#9ca3af;"><i class="fas fa-lock" style="font-size:11px;"></i> @lang('public.username_readonly')</span>
            </div>
        </div>
    </div>

    <div class="form-group row align-items-center mb-3">
        <label for="email" class="col-sm-3 col-form-label" style="font-weight:500;font-size:14px;color:#374151;">@lang('public.email_label')</label>
        <div class="col-sm-9">
            <input id="email" name="email" type="email" class="form-control"
                value="{{ old('email', $user->email) }}" required autocomplete="email"
                style="font-size:14px;">
            @if ($errors->get('email'))
                <div class="text-danger mt-1" style="font-size:13px;">{{ $errors->get('email')[0] }}</div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-sm-9 offset-sm-3 d-flex align-items-center" style="gap:12px;">
            <button type="submit" class="btn btn-primary">@lang('public.save')</button>
            @if (session('status') === 'profile-updated')
                <span class="text-success" style="font-size:14px;font-weight:500;">
                    <i class="fas fa-check mr-1"></i>@lang('public.saved')
                </span>
            @endif
        </div>
    </div>
</form>
