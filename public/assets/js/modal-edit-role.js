/**
 * Update Role Modal JS
 */
'use strict';

document.addEventListener('DOMContentLoaded', function (e) {
  const updateRoleForm = document.getElementById('updateRoleForm');
  const fvUpdate = FormValidation.formValidation(updateRoleForm, {
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
        eleValidClass: '',
        rowSelector: '.col-12'
      }),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  });

  updateRoleForm.addEventListener('submit', function (e) {
    e.preventDefault();
    fvUpdate.validate().then(function (status) {
      if (status == 'Valid') {
        const roleId = document.getElementById('editRoleId').value;
        const roleName = document.getElementById('editName').value;
        const permissions = [];
        console.log(roleId);
        console.log(roleName);

        document.querySelectorAll('input[name="permissions[]"]:checked').forEach(checkbox => {
          permissions.push(checkbox.value);
        });

        const formData = new FormData();
        formData.append('name', roleName);
        formData.append('_method', 'PUT'); 
        permissions.forEach(permission => {
          formData.append('permissions[]', permission);
        });

        fetch(`/roles/${roleId}`, {
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
              Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Role has been updated successfully.',
                customClass: {
                  confirmButton: 'btn btn-primary'
                }
              });

              const modal = document.getElementById('editRoleModal');
              const bsModal = bootstrap.Modal.getInstance(modal);
              bsModal.hide();
              updateRoleForm.reset();
              $('.datatables-roles').DataTable().ajax.reload();
            } else {
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

  $(document).on('click', '.edit-role-btn', function () {
    let roleId = $(this).data('id');
    $.ajax({
      url: `/roles/${roleId}/edit`,
      type: 'GET',
      success: function (response) {
        $('#editRoleId').val(response.role.id);
        $('#editName').val(response.role.name);

        let permissionsTable = $('#editPermissionsTable');
        permissionsTable.empty();
        let allRow = `<tr>
                                        <td class="text-nowrap fw-medium text-heading">
                                            Administrator Access
                                            <i class="ti ti-info-circle" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Allows a full access to the system"></i>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <div class="form-check mb-0 me-4 me-lg-12">
                                                    <input class="form-check-input" type="checkbox" id="selectAll" />
                                                    <label class="form-check-label" for="selectAll"> Select All
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>`;
        permissionsTable.append(allRow);
        $.each(response.permissions, function (group, actions) {
          let row = `<tr>
                      <td class="text-nowrap fw-medium text-heading">${group}</td>
                      <td>
                          <div class="d-flex justify-content-end">`;

          ['read', 'write', 'create', 'delete', 'index'].forEach(function (
            permission) {
            let isChecked = response.rolePermissions.includes(
              `${group}.${permission}`) ? 'checked' : '';

            if (actions.includes(permission)) {
              row += `
                            <div class="form-check mb-0 me-4 me-lg-12">
                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                    value="${group}.${permission}" id="${group}-${permission}" ${isChecked}>
                                <label class="form-check-label" for="${group}-${permission}">
                                    ${permission}
                                </label>
                            </div>`;
            }
          });
          row += `</div></td></tr>`;
          permissionsTable.append(row);
        });
        $('#editRoleModal').modal('show');
      }
    });
  });

});
