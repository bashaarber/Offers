<section>
    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_current_password" class="form-label" style="font-weight: 500; font-size: 14px;">{{ __('Current Password') }}</label>
            <div style="position: relative;">
                <input id="update_current_password" name="current_password" type="password"
                    class="form-control" autocomplete="current-password" style="padding-right: 44px;">
                <button type="button" onclick="togglePwdVisibility('update_current_password', this)" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:#6b7280; cursor:pointer; padding:4px;">
                    <i class="fa-solid fa-eye eye-open" style="font-size:16px;"></i>
                    <i class="fa-solid fa-eye-slash eye-closed" style="font-size:16px; display:none;"></i>
                </button>
            </div>
            @if ($errors->updatePassword->get('current_password'))
                <div class="text-danger mt-1" style="font-size: 13px;">{{ $errors->updatePassword->get('current_password')[0] }}</div>
            @endif
        </div>

        <div class="mb-3">
            <label for="update_password" class="form-label" style="font-weight: 500; font-size: 14px;">{{ __('New Password') }}</label>
            <div style="position: relative;">
                <input id="update_password" name="password" type="password" class="form-control"
                    autocomplete="new-password" style="padding-right: 44px;">
                <button type="button" onclick="togglePwdVisibility('update_password', this)" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:#6b7280; cursor:pointer; padding:4px;">
                    <i class="fa-solid fa-eye eye-open" style="font-size:16px;"></i>
                    <i class="fa-solid fa-eye-slash eye-closed" style="font-size:16px; display:none;"></i>
                </button>
            </div>
            @if ($errors->updatePassword->get('password'))
                <div class="text-danger mt-1" style="font-size: 13px;">{{ $errors->updatePassword->get('password')[0] }}</div>
            @endif
        </div>

        <div class="mb-3">
            <label for="update_password_confirmation" class="form-label" style="font-weight: 500; font-size: 14px;">{{ __('Confirm Password') }}</label>
            <div style="position: relative;">
                <input id="update_password_confirmation" name="password_confirmation" type="password"
                    class="form-control" autocomplete="new-password" style="padding-right: 44px;">
                <button type="button" onclick="togglePwdVisibility('update_password_confirmation', this)" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:#6b7280; cursor:pointer; padding:4px;">
                    <i class="fa-solid fa-eye eye-open" style="font-size:16px;"></i>
                    <i class="fa-solid fa-eye-slash eye-closed" style="font-size:16px; display:none;"></i>
                </button>
            </div>
            @if ($errors->updatePassword->get('password_confirmation'))
                <div class="text-danger mt-1" style="font-size: 13px;">{{ $errors->updatePassword->get('password_confirmation')[0] }}</div>
            @endif
        </div>

        <div class="d-flex align-items-center" style="gap: 12px;">
            <button type="submit" class="btn btn-primary">{{ __('Save Password') }}</button>
            @if (session('status') === 'password-updated')
                <span class="text-success" style="font-size: 14px; font-weight: 500;"><i class="fa-solid fa-check mr-1"></i>{{ __('Password updated!') }}</span>
            @endif
        </div>
    </form>

    <script>
        function togglePwdVisibility(inputId, btn) {
            var input = document.getElementById(inputId);
            var eyeOpen = btn.querySelector('.eye-open');
            var eyeClosed = btn.querySelector('.eye-closed');
            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'inline';
            } else {
                input.type = 'password';
                eyeOpen.style.display = 'inline';
                eyeClosed.style.display = 'none';
            }
        }
    </script>
</section>
