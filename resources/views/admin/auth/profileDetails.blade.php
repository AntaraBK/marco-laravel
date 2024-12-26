@extends('admin.layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-6">
                <!-- Account -->
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-6">
                        <img src="../../assets/img/avatars/1.png" alt="user-avatar" class="d-block w-px-100 h-px-100 rounded"
                            id="uploadedAvatar" />
                        <div class="button-wrapper">
                            <label for="upload" class="btn btn-primary me-3 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Upload new photo</span>
                                <i class="ti ti-upload d-block d-sm-none"></i>
                                <input type="file" id="upload" class="account-file-input" hidden
                                    accept="image/png, image/jpeg" />
                            </label>
                            <button type="button" class="btn btn-label-secondary account-image-reset mb-4">
                                <i class="ti ti-refresh-dot d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Reset</span>
                            </button>

                            <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div id="alert-container"></div>
                    <form id="formAccountSettings" method="POST" onsubmit="return false">
                        @csrf
                        <input type="hidden" name="id" value="{{$user->id}}" />
                        <div class="row">
                            <div class="mb-4 col-md-6">
                                <label for="name" class="form-label">Name</label>
                                <input class="form-control" type="text" id="name" name="name"
                                    value="{{ $user->name }}" autofocus />
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input class="form-control" type="text" id="email" name="email"
                                    value="{{ $user->email }}" placeholder="john.doe@example.com" />
                            </div>
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="mobile">Phone Number</label>
                                <input type="text" id="mobile" name="mobile" class="form-control" maxlength="10"
                                    value="{{ $user->mobile }}" placeholder="202 555 0111" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-3" id="saveProfileBtn">Save changes</button>
                            <button type="reset" class="btn btn-label-secondary">Cancel</button>
                        </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/pages-account-settings-account.js') }}"></script>
    <script>
        const updateProfileUrl = "{{ route('update.profile') }}";
    </script>
@endsection
