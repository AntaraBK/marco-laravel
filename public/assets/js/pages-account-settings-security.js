'use strict';

document.addEventListener('DOMContentLoaded', function () {

    const savePasswordBtn = document.getElementById('savePasswordBtn');
    if (savePasswordBtn) {
        savePasswordBtn.addEventListener('click', function () {
            $('.invalid-feedback').html("");

            const formData = new FormData(document.getElementById('formAccountSettings'));
            $.ajax({
                url: changePasswordUrl  ,
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
        });
    }

    const formChangePass = document.querySelector('#formAccountSettings');
    if (formChangePass) {
        const fv = FormValidation.formValidation(formChangePass, {
            fields: {
                currentPassword: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter your current password'
                        },
                        stringLength: {
                            min: 8,
                            message: 'Password must be at least 8 characters'
                        }
                    }
                },
                newPassword: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter a new password'
                        },
                        stringLength: {
                            min: 8,
                            message: 'Password must be at least 8 characters'
                        }
                    }
                },
                confirmPassword: {
                    validators: {
                        notEmpty: {
                            message: 'Please confirm your new password'
                        },
                        identical: {
                            compare: function () {
                                return formChangePass.querySelector('[name="newPassword"]').value;
                            },
                            message: 'The password and its confirmation are not the same'
                        },
                        stringLength: {
                            min: 8,
                            message: 'Password must be at least 8 characters'
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
    }
});

function createAlertHtml(message) {
    return `<div class="alert alert-success alert-dismissible" role="alert">
                         ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>`;
}
