$(document).on('click', '.delete-record', function() {
  var deleteUrl = $(this).data(
  'url'); // Assuming you're passing the delete URL in the 'data-url' attribute
  var userId = $(this).data('id'); // Get the user ID from the data-id attribute
  var csrfToken = $('meta[name="csrf-token"]').attr('content');
  console.log(table);
  Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      customClass: {
          confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
          cancelButton: 'btn btn-label-secondary waves-effect waves-light'
      },
      buttonsStyling: false
  }).then(function(result) {
      if (result.value) {
          $.ajax({
              url: deleteUrl, // The delete URL
              type: 'DELETE',
              data: {
                  id: userId, // Pass the user ID
                  _token: csrfToken
              },
              success: function(response) {
                  // If the deletion is successful, remove the row from DataTable
                  if (response.success) {
                      table.row($(this).closest('tr')).remove().draw();
                      Swal.fire({
                          icon: 'success',
                          title: 'Deleted!',
                          text: 'Your file has been deleted.',
                          customClass: {
                              confirmButton: 'btn btn-success waves-effect waves-light'
                          }
                      });
                  } else {
                      Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'There was an error deleting the record.',
                          customClass: {
                              confirmButton: 'btn btn-danger waves-effect waves-light'
                          }
                      });
                  }
              },
              error: function(xhr, status, error) {
                  Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: 'There was an error processing your request.',
                      customClass: {
                          confirmButton: 'btn btn-danger waves-effect waves-light'
                      }
                  });
              }
          });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
          Swal.fire({
              title: 'Cancelled',
              text: 'Your imaginary file is safe :)',
              icon: 'error',
              customClass: {
                  confirmButton: 'btn btn-success waves-effect waves-light'
              }
          });
      }
  });
});