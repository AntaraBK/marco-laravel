@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">User Create</h5>
                <div class="card-body">
                    <form id="userForm" class="row g-6" method="POST" action="{{ route('users.store') }}">
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label" for="username">Name</label>
                            <input type="text" id="username" class="form-control" placeholder="John Doe"
                                name="username" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="email">Email</label>
                            <input class="form-control" type="email" id="email" name="email"
                                placeholder="john.doe" />
                        </div>
                        <div class="col-md-6">
                            <label for="mobile" class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" id="mobile" name="mobile"
                                placeholder="Mobile number" maxlength="10" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="roles">Roles</label>
                            <select id="roles" name="roles" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" name="submitButton" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/users.js') }}"></script>
@endsection
