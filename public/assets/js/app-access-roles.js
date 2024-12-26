/**
 * App user list
 */

'use strict';

// Datatable (jquery)


var dt_ajax_table = $('.datatables-roles');

if (dt_ajax_table.length) {
    dt_ajax_table.DataTable({
        processing: true,
        serverSide: true,
        ajax: roleListUrl, // Update the route name as needed
        columns: [
            { data: 'name', name: 'name' },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end mt-n6 mt-md-0"f>>' +
            '<"table-responsive"t>' +
            '<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        language: {
            paginate: {
                next: '<i class="ti ti-chevron-right ti-sm"></i>',
                previous: '<i class="ti ti-chevron-left ti-sm"></i>'
            }
        }
    });
}
