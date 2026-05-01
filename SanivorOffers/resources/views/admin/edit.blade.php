<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
    @include('layouts.sidebar')
    <div class="content">
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="font-weight-bold">Edit User</h3>
                            <form method="POST" action="{{ route('user.update', $user) }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="username" class="form-label">{{ __('Username') }}</label>
                                    <input id="username" class="form-control" type="text" name="username" value="{{ old('username', $user->username) }}" required>
                                    @error('username')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Email') }}</label>
                                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="role" class="form-label">{{ __('User Type') }}</label>
                                    <select id="role" name="role" class="form-control" required>
                                        <option value="seller" {{ old('role', $user->role) === 'seller' ? 'selected' : '' }}>Seller</option>
                                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    @error('role')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">{{ __('New Password (optional)') }}</label>
                                    <input id="password" class="form-control" type="password" name="password" autocomplete="new-password">
                                    @error('password')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">{{ __('Confirm New Password') }}</label>
                                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" autocomplete="new-password">
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a href="{{ route('user.index') }}" class="btn btn-secondary ml-2">@lang('public.back')</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
