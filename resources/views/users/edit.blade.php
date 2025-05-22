@extends('admin.layout.app')
@section('title', 'Edit User')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url('admin/user') }}">Back</a>
                <form id="edit_farmer" action="{{ route('user.update', $user->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('POST') <!-- Use PUT method for editing -->
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Edit User</h4>
                                <div class="row mx-0 px-4">



                                    <!-- name Field -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="name" class="form-control" id="name" name="name"
                                                placeholder="Enter your name" value="{{ $user->name }}" required>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <!-- name Field -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="{{ $user->email }}" placeholder="example@gmail.com" required>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <!-- Phone Field -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group">
                                            <label for="Phone">Phone</label>
                                            <input type="phone" class="form-control" id="phone" name="phone"
                                                value="{{ $user->phone }}" placeholder="Enter your phone" required>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <!--  Image feild-->
                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group">
                                            <label for="image">Image</label>
                                            <input type="file" class="form-control" id="image" name="image"
                                                value="" placeholder="Select date" required>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div> --}}

                                    <!-- Password Field -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group position-relative">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password">
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
                    </div>
                </form>
            </div>
        </section>
    </div>

@endsection

@section('js')
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
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
