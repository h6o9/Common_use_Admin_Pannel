@extends('admin.layout.app')
@section('title', 'Edit Sub Admin')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                <form id="edit_subadmin" action="{{ route('subadmin.update', $subAdmin->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('POST') <!-- Use PUT method for editing -->
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Edit SubAdmin</h4>
                                <div class="row mx-0 px-4">
                                    <!-- Name Field -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ $subAdmin->name }}" required placeholder="Enter name" autofocus>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <!-- Email Field -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="text" class="form-control" id="email" name="email"
                                                value="{{ $subAdmin->email }}" required placeholder="Enter email" autofocus>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <!-- phone Field -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group">
                                            <label for="tel">Phone</label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                value="{{ $subAdmin->phone }}" required placeholder="Enter phone number"
                                                autofocus>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    {{-- Role --}}
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group">
                                            <label for="role">Select Role</label>
                                            <select class="form-control" id="role" name="role">
                                                <option value="">-- Select Role --</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                        {{ isset($currentRoleId) && $currentRoleId == $role->id ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Status Dropdown -->
                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="" disabled>Select an Option</option>
                                                <option value="1" {{ $subAdmin->status == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ $subAdmin->status == 0 ? 'selected' : '' }}>Deactive</option>
                                            </select>
                                        </div>
                                    </div> --}}

                                    <!-- Image Upload -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group">
                                            <label for="image">Image</label>
                                            <input type="file" class="form-control" id="image" name="image"
                                                placeholder="Upload Image" autofocus>
                                            <div class="mt-2">
                                                @if ($subAdmin->image)
                                                    <img src="{{ asset($subAdmin->image) }}" alt="Image" width="100">
                                                @endif
                                            </div>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <!-- Password -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group position-relative">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                placeholder="Enter password" tabindex="2" autofocus>
                                            <span class="fa fa-eye position-absolute"
                                                style="top: 42px; right: 15px; cursor: pointer;"
                                                onclick="togglePassword()"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="card-footer text-center row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary mr-1 btn-bg" id="submit">Save
                                            Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@section('js')
    @if (session('success'))
        <script>
            toastr.success('{{ session('success') }}');
        </script>
    @endif
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = event.target;
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
@endsection
