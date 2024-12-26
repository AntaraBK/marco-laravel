@extends('admin.auth.layouts.app')
@section('content')
     <!-- Forgot Password -->

        <!-- /Logo -->
        <h4 class="mb-1">Forgot Password? 🔒</h4>
        <p class="mb-6">Enter your email and we'll send you instructions to reset your password</p>
        <form id="formAuthentication" class="mb-6" action="auth-reset-password-basic.html" method="GET">
          <div class="mb-6">
            <label for="email" class="form-label">Email</label>
            <input
              type="text"
              class="form-control"
              id="email"
              name="email"
              placeholder="Enter your email"
              autofocus />
          </div>
          <button class="btn btn-primary d-grid w-100">Send Reset Link</button>
        </form>
        <div class="text-center">
          <a href="auth-login-basic.html" class="d-flex justify-content-center">
            <i class="ti ti-chevron-left scaleX-n1-rtl me-1_5"></i>
            Back to login
          </a>
        </div>
    <!-- /Forgot Password -->

<script>
  $(document).ready(function() {
    $('#forgotPasswordForm').on('submit', function(e) {
      e.preventDefault();

      $('#forgot-btn').attr("disabled","true");
      // Clear previous errors
      $('.text-danger').remove();
      $('#alert-message').removeClass('alert-success alert-danger d-none').text('');

      $.ajax({
        url: "{{ route('forget.password.post') }}", // Your password reset route
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
          $('#forgot-btn').removeAttr("disabled");
          if (response.success) {
            
            // Show success message
            $('#alert-message').addClass('alert-success').text(response.message).removeClass('d-none');
          } else {
            // Show error message returned from the server
            $('#alert-message').addClass('alert-danger').text(response.message).removeClass('d-none');
          }
        },
        error: function(xhr) {
          $('#forgot-btn').removeAttr("disabled");
          
          // Handle validation errors
          let errors = xhr.responseJSON.errors;

          if (errors.email) {
            $('input[name="email"]').parent().after(`
              <div class="text-danger">${errors.email[0]}</div>
            `);
          }
        }
      });
    });
  });
</script>
@endsection
