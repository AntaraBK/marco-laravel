/**
 * Add new role Modal JS
 */

'use strict';

document.addEventListener('DOMContentLoaded', function (e) {
    // add role form validation
    const addRoleForm = document.getElementById('addRoleForm');
    const fv = FormValidation.formValidation(addRoleForm, {
      fields: {
        name: {
          validators: {
            notEmpty: {
              message: 'Please enter role name'
            }
          }
        },
        permissions: {
          validators: {
            notEmpty: {
              message: 'Please select at least one permission'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          // eleInvalidClass: '',
          eleValidClass: '',
          rowSelector: '.col-12'
        }),
        // submitButton: new FormValidation.plugins.SubmitButton(),
        // Submit the form when all fields are valid
        // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
        autoFocus: new FormValidation.plugins.AutoFocus()
      }
    });

    // Select All checkbox click
    const selectAll = document.querySelector('#selectAll'),
      checkboxList = document.querySelectorAll('[name="permissions[]"]');
    selectAll.addEventListener('change', t => {
      checkboxList.forEach(e => {
        e.checked = t.target.checked;
      });
    });


    // Form submission logic
    // addRoleForm.addEventListener('submit', function (e) {
    //   e.preventDefault();
    //   alert("Sss");

    // });
    addRoleForm.addEventListener('submit', function (e) {
      e.preventDefault();
      alert('hgjhg');
      // Validate form
      fv.validate().then(function (status) {
        if (status === 'Valid') {
          // Get form data
          const roleName = document.getElementById('name').value;
          const permissions = [];
          
          // Get selected permissions
          document.querySelectorAll('input[name="permissions[]"]:checked').forEach(checkbox => {
            permissions.push(checkbox.value);
          });

          // Create form data object
          const formData = new FormData();
          formData.append('name', roleName);
          permissions.forEach(permission => {
            formData.append('permissions[]', permission);
          });

          // Send AJAX request
          fetch('/roles', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
              'Accept': 'application/json'
            },
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            console.log(data);
            if (data.success) {
              // Show success message
              Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Role has been created successfully.',
                customClass: {
                  confirmButton: 'btn btn-primary'
                }
              });

              // Close modal
              const modal = document.getElementById('addRoleModal');
              const bsModal = bootstrap.Modal.getInstance(modal);
              bsModal.hide();

              // Reset form
              addRoleForm.reset();
              
              // Refresh DataTable
              $('.datatables-roles').DataTable().ajax.reload();
            } else {
              // Show error message
              Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message || 'Something went wrong!',
                customClass: {
                  confirmButton: 'btn btn-primary'
                }
              });
            }
          })
          .catch(error => {
            console.error('Error:', error);
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'Something went wrong!',
              customClass: {
                confirmButton: 'btn btn-primary'
              }
            });
          });
        }
      });
    });
});
