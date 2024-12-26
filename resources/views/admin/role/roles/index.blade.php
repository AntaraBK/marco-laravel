@extends('admin.layouts.app')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Role List</h5>
            <button data-bs-target="#addRoleModal" data-bs-toggle="modal"
                class="btn btn-primary waves-effect waves-light ml-auto add-new-role">
                Add New Role
            </button>
        </div>
        <div class="card-datatable text-nowrap">
            <table class="datatables-roles table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Add Role Modal -->
    <!-- Add Role Modal -->
    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-6">
                        <h4 class="role-title mb-2">Add New Role</h4>
                        <p>Set role permissions</p>
                    </div>
                    <!-- Add role form -->
                    <form id="addRoleForm" class="row g-6" onsubmit="return false">
                        <div class="col-12">
                            <label class="form-label" for="modalRoleName">Role Name</label>
                            <input type="text" id="modalRoleName" name="modalRoleName" class="form-control"
                                placeholder="Enter a role name" tabindex="-1" />
                        </div>
                        <div class="col-12">
                            <h5 class="mb-6">Role Permissions</h5>
                            <!-- Permission table -->
                            <div class="table-responsive">
                                <table class="table table-flush-spacing">
                                    <tbody>
                                        <tr>
                                            <td class="text-nowrap fw-medium text-heading">
                                                Administrator Access
                                                <i class="ti ti-info-circle" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Allows a full access to the system"></i>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-end">
                                                    <div class="form-check mb-0">
                                                        <input class="form-check-input" type="checkbox" id="selectAll" />
                                                        <label class="form-check-label" for="selectAll"> Select All
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                        // Group permissions by their prefix (everything before the dot)
                                        $groupedPermissions = [];
                                        foreach ($permissions as $permission) {
                                            $parts = explode('.', $permission->name);
                                            $group = $parts[0];
                                            $action = $parts[1];
                                        
                                            if (!isset($groupedPermissions[$group])) {
                                                $groupedPermissions[$group] = [];
                                            }
                                            $groupedPermissions[$group][] = $action;
                                        }
                                        ?>
                                         @foreach ($groupedPermissions as $group => $actions)
                                         <tr>
                                             <td class="text-nowrap fw-medium text-heading">
                                                 {{ ucfirst($group) }}
                                             </td>
                                             <td>
                                                 <div class="d-flex justify-content-end">
                                                     @foreach(['read', 'write', 'create', 'delete','index'] as $action)
                                                         @if(in_array($action, $actions))
                                                         <div class="form-check mb-0 me-4 me-lg-12">
                                                             <input 
                                                                 class="form-check-input" 
                                                                 type="checkbox" 
                                                                 id="{{ $group . ucfirst($action) }}"
                                                                 name="permissions[]"
                                                                 value="{{ $group . '.' . $action }}"
                                                                 @if(isset($rolePermissions) && in_array($group . '.' . $action, $rolePermissions)) checked @endif
                                                             />
                                                             <label class="form-check-label" for="{{ $group . ucfirst($action) }}">
                                                                 {{ ucfirst($action) }}
                                                             </label>
                                                         </div>
                                                         @endif
                                                     @endforeach
                                                 </div>
                                             </td>
                                         </tr>
                                         @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Permission table -->
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-3">Submit</button>
                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </form>
                    <!--/ Add role form -->
                </div>
            </div>
        </div>
    </div>
    <!--/ Add Role Modal -->
@endsection
@section('script')
    <script src="{{ asset('assets/js/modal-add-role.js') }}"></script>
    <script>
        var dt_ajax_table = $('.datatables-roles');
        var table = dt_ajax_table.DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('roles.index') }}",
            columns: [{
                    data: 'name',
                    name: 'name'
                },
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
    </script>
    <script src="{{ asset('assets/js/deleteData.js') }}"></script>
@endsection
