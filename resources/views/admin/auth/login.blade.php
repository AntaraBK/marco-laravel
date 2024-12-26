@extends('admin.auth.layouts.app')
@section('content')
    <!-- /.login-logo -->
    <!-- Login -->
    <!-- /Logo -->
    <h4 class="mb-1">Welcome to Vuexy! 👋</h4>
    <p class="mb-6">Please sign-in to your account and start the adventure</p>

    <form id="loginForm" method="POST">
        @csrf
        <div class="mb-6">
            <label for="email" class="form-label">Email or Username</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email or username"
                autofocus />
        </div>
        <div class="mb-6 form-password-toggle">
            <label class="form-label" for="password">Password</label>
            <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
            </div>
        </div>
        <div class="my-8">
            <div class="d-flex justify-content-between">
                <div class="form-check mb-0 ms-2">
                    <input class="form-check-input" type="checkbox" id="remember-me" />
                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                </div>
                <a href="{{ route('forget.password.get') }}">
                    <p class="mb-0">Forgot Password?</p>
                </a>
            </div>
        </div>
        <div class="mb-6">
            <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
        </div>
    </form>

    <p class="text-center">
        <span>New on our platform?</span>
        <a href="{{ route('registration') }}">
            <span>Create an account</span>
        </a>
    </p>

    <div class="divider my-6">
        <div class="divider-text">or</div>
    </div>

    <div class="d-flex justify-content-center">
        <a href="javascript:;" class="btn btn-sm btn-icon rounded-pill btn-text-facebook me-1_5">
            <i class="tf-icons ti ti-brand-facebook-filled"></i>
        </a>

        <a href="javascript:;" class="btn btn-sm btn-icon rounded-pill btn-text-twitter me-1_5">
            <i class="tf-icons ti ti-brand-twitter-filled"></i>
        </a>

        <a href="javascript:;" class="btn btn-sm btn-icon rounded-pill btn-text-github me-1_5">
            <i class="tf-icons ti ti-brand-github-filled"></i>
        </a>

        <a href="javascript:;" class="btn btn-sm btn-icon rounded-pill btn-text-google-plus">
            <i class="tf-icons ti ti-brand-google-filled"></i>
        </a>
    </div>

    <!-- /Register -->
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                // Clear previous errors
                $('.text-danger').remove();
                $('.alert-danger').remove();

                $.ajax({
                    url: "{{ route('login.post') }}", // Your login route
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            window.location.href = response.redirect_url;
                        } else {
                            // Display error message returned from the server
                            $('#loginForm').prepend(`
              <div class="alert alert-danger" role="alert">${response.message}</div>
            `);
                        }
                    },
                    error: function(xhr) {
                        // Handle validation errors
                        let errors = xhr.responseJSON.errors;

                        if (errors) {
                            if (errors.email) {
                                $('input[name="email"]').parent().after(`
                <div class="text-danger">${errors.email[0]}</div>
              `);
                            }
                            if (errors.password) {
                                $('input[name="password"]').parent().after(`
                <div class="text-danger">${errors.password[0]}</div>
              `);
                            }
                        } else {
                            if (xhr.responseJSON.message) {
                                $('#loginForm').prepend(`
                <div class="alert alert-danger" role="alert">${xhr.responseJSON.message}</div>
              `);
                            }
                        }
                    }
                });
            });
        });
    </script>
@endsection
