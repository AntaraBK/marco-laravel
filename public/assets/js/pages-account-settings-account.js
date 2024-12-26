document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    const formAccSettings = document.querySelector('#formAccountSettings');
    const form = formAccSettings;

    // Form validation for profile update
    if (form) {
      const fv = FormValidation.formValidation(form, {
        fields: {
          name: {
            validators: {
              notEmpty: {
                message: 'Please enter your name'
              }
            }
          },
          email: {
            validators: {
              notEmpty: {
                message: 'Please enter your email'
              },
              emailAddress: {
                message: 'Please enter a valid email address'
              }
            }
          },
          mobile: {
            validators: {
              notEmpty: {
                message: 'Please enter your phone number'
              },
              stringLength: {
                min: 10,
                max: 10,
                message: 'Please enter a valid 10-digit phone number'
              }
            }
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: '',
            rowSelector: '.col-md-6'
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),
          autoFocus: new FormValidation.plugins.AutoFocus()
        }
      });

      const saveProfileBtn = document.getElementById('saveProfileBtn');
      if (saveProfileBtn) {
        saveProfileBtn.addEventListener('click', function (event) {
          event.preventDefault(); // Prevent the default form submission
          // Validate the form
          fv.validate().then(function (status) {
            if (status == 'Valid') {
              // Create a FormData object from the form
              const formData = new FormData(form);
              $.ajax({
                url: updateProfileUrl  ,
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    const alertHtml = createAlertHtml(response.success);
                    $('#alert-container').html(alertHtml);
                    setTimeout(function() {
                        $('.alert').alert('close');
                    }, 5000);
                    $('#formAccountSettings')[0].reset();
                },
                error: function(response) {
                    const errors = response.responseJSON.errors;
                    console.log(response.responseJSON.errors);
                    $.each(errors, function(key, value) {
                        console.log(key);
                        const element = $(`[name=${key}]`);
                        element.addClass('is-invalid');
                        element.closest('.form-password-toggle').find('.invalid-feedback').remove();
                        element.closest('.form-password-toggle').append(`<span class="invalid-feedback">${value[0]}</span>`);
                    });
                }
            });

            }
          });
        });
      }
    }

    // Handle avatar image upload and reset
    let accountUserImage = document.getElementById('uploadedAvatar');
    const fileInput = document.querySelector('.account-file-input'),
      resetFileInput = document.querySelector('.account-image-reset');

    if (accountUserImage) {
      const resetImage = accountUserImage.src;
      fileInput.onchange = () => {
        if (fileInput.files[0]) {
          accountUserImage.src = window.URL.createObjectURL(fileInput.files[0]);
        }
      };
      resetFileInput.onclick = () => {
        fileInput.value = '';
        accountUserImage.src = resetImage;
      };
    }
  })();
});

// Function to create success/error alert HTML
function createAlertHtml(success, message) {
  if (success) {
    return `<div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Success!</strong> ${message}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
  } else {
    return `<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Error!</strong> ${message}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
  }
}
