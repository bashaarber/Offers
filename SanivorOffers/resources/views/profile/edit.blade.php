@include('layouts.sidebar')
<div class="content">
    <div class="container py-4">
        <h2 class="mb-4" style="color: #333; font-weight: 600;">Profile Settings</h2>

        <div class="card mb-4" style="border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-radius: 12px;">
            <div class="card-body p-4">
                <h5 style="font-weight: 600; margin-bottom: 4px;">Profile Information</h5>
                <p class="text-muted mb-4" style="font-size: 14px;">Update your account's profile information and email address.</p>
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="card mb-4" style="border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-radius: 12px;">
            <div class="card-body p-4">
                <h5 style="font-weight: 600; margin-bottom: 4px;">Update Password</h5>
                <p class="text-muted mb-4" style="font-size: 14px;">Ensure your account is using a long, random password to stay secure.</p>
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="card mb-4" style="border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-radius: 12px; border-left: 4px solid #dc3545 !important;">
            <div class="card-body p-4">
                <h5 style="font-weight: 600; margin-bottom: 4px; color: #dc3545;">Delete Account</h5>
                <p class="text-muted mb-4" style="font-size: 14px;">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
