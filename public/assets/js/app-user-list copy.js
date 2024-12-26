

$(document).ready(function () {
  var dt_ajax_table = $('.datatables-ajax');

  if (dt_ajax_table.length) {
      dt_ajax_table.DataTable({
          processing: true, // Enables the "Processing" indicator
          serverSide: true, // Enables server-side processing
          ajax: {
              url: userListUrl, // Correct route to fetch data
              type: 'GET',
              error: function (xhr, error, code) {
                  console.error("An error occurred while loading data:", xhr.responseText);
              }
          },
          columns: [
              { data: 'name', name: 'name', title: 'Name' },
              { data: 'email', name: 'email', title: 'Email' },
              { data: 'mobile', name: 'mobile', title: 'Phone Number' }
          ],
          dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end mt-n6 mt-md-0"f>>' +
               '<"table-responsive"t>' +
               '<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
          language: {
              processing: "Loading data...", // Message shown while processing
              paginate: {
                  next: 'Next →', // Customize pagination text
                  previous: '← Previous'
              },
              emptyTable: "No data available in table",
              info: "Showing _START_ to _END_ of _TOTAL_ entries",
              infoEmpty: "No entries to show",
              lengthMenu: "Show _MENU_ entries"
          },
          responsive: true // Ensures the table adjusts for different screen sizes
      });
  }
});
