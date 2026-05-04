<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User</title>
</head>
<body>
    @include('layouts.sidebar')
    <div class="content">
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <header>
                                <h3 class="font-weight-bold">Create New User</h3>
                            </header>
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="username" class="form-label">{{ __('Username') }}</label>
                                    <input id="username" class="form-control" type="text" name="username"
                                        value="{{ old('username') }}" required autocomplete="username">
                                    @if ($errors->has('username'))
                                        <div class="text-danger mt-2">{{ $errors->first('username') }}</div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Email') }}</label>
                                    <input id="email" class="form-control" type="email" name="email"
                                        value="{{ old('email') }}" required autocomplete="username">
                                    @if ($errors->has('email'))
                                        <div class="text-danger mt-2">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">{{ __('Password') }}</label>
                                    <input id="password" class="form-control" type="password" name="password" required
                                        autocomplete="new-password">
                                    @if ($errors->has('password'))
                                        <div class="text-danger mt-2">{{ $errors->first('password') }}</div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation"
                                        class="form-label">{{ __('Confirm Password') }}</label>
                                    <input id="password_confirmation" class="form-control" type="password"
                                        name="password_confirmation" required autocomplete="new-password">
                                    @if ($errors->has('password_confirmation'))
                                        <div class="text-danger mt-2">{{ $errors->first('password_confirmation') }}</div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="role" class="form-label">{{ __('User Type') }}</label>
                                    <select id="role" name="role" class="form-control" required>
                                        <option value="seller" {{ old('role', 'seller') === 'seller' ? 'selected' : '' }}>Seller</option>
                                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    @if ($errors->has('role'))
                                        <div class="text-danger mt-2">{{ $errors->first('role') }}</div>
                                    @endif
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">{{ __('Register') }}</button>
                                    <a href="{{ url('/users') }}" class="btn btn-secondary ml-2">@lang('public.back')</a>
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
