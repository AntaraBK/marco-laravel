'use strict';


document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    const userForm = document.getElementById('userForm');

    const fv = FormValidation.formValidation(userForm, {
      fields: {
        username: {
          validators: {
            notEmpty: {
              message: 'Please enter your name'
            },
            stringLength: {
              min: 6,
              max: 30,
              message: 'The name must be more than 6 and less than 30 characters long'
            },
            regexp: {
              regexp: /^[a-zA-Z0-9 ]+$/,
              message: 'The name can only consist of alphabetical, number and space'
            }
          }
        },
        email: {
          validators: {
            notEmpty: {
              message: 'Please enter your email'
            },
            emailAddress: {
              message: 'The value is not a valid email address'
            }
          }
        },
        mobile: {
          validators: {
            notEmpty: {
              message: 'Please enter your mobile number'
            },
            regexp: {
              regexp: /^[0-9]{10}$/,
              message: 'The mobile number must be 10 digits long and contain only numbers'
            }
          }
        },
        roles: {
          validators: {
            notEmpty: {
              message: 'Please select a role'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          eleValidClass: 'is-valid',
          eleInvalidClass: 'is-invalid',
          rowSelector: function (field, ele) {
            return '.row';
          }
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        // Submit the form when all fields are valid
        defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
        autoFocus: new FormValidation.plugins.AutoFocus()
      },
      init: instance => {
        instance.on('plugins.message.placed', function (e) {
          //* Move the error message out of the `input-group` element
          if (e.element.parentElement.classList.contains('input-group')) {
            // `e.field`: The field name
            // `e.messageElement`: The message element
            // `e.element`: The field element
            e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
          }
          //* Move the error message out of the `row` element for custom-options
          if (e.element.parentElement.parentElement.classList.contains('custom-option')) {
            e.element.closest('.row').insertAdjacentElement('afterend', e.messageElement);
          }
        });
      }
    });

  })();
});
