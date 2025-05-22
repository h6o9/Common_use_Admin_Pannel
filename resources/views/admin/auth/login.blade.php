@extends('admin.auth.layout.app')
@section('title', 'Login')

@section('content')
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    <div class="card card-primary">
                        <div class="card-header" style="display: flex; justify-content: center;">
                            <img src="{{ asset('public/admin/assets/img/logo.png') }}" style="width: 50%; height: 50%;"
                                class="img-fluid rounded-circle" alt="Logo">
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ url('login') }}" class="needs-validation" novalidate>
                                @csrf

                                <!-- Email Field -->
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="email" class="form-control" name="email" tabindex="1"
                                        required autofocus placeholder="example@gmail.com">
                                    @error('email')
                                        <span style="color: red;">Email required</span>
                                    @enderror
                                </div>

                                <!-- Password Field with Toggle -->
                                <div class="form-group" style="margin-bottom: 0.5rem; position: relative;">
                                    <label>Password</label>
                                    <input type="password" placeholder="Enter Password" name="password" id="password"
                                        class="form-control" style="padding-right: 2.5rem;">
                                    <span
                                        onclick="
                                        const passwordField = document.getElementById('password');
                                        const icon = this;
                                        if (passwordField.type === 'password') {
                                            passwordField.type = 'text';
                                            icon.classList.remove('fa-eye');
                                            icon.classList.add('fa-eye-slash');
                                        } else {
                                            passwordField.type = 'password';
                                            icon.classList.remove('fa-eye-slash');
                                            icon.classList.add('fa-eye');
                                        }
                                    "
                                        class="fa fa-eye"
                                        style="
                                        position: absolute;
                                        top: 2.67rem;
                                        right: 0.5rem;
                                        cursor: pointer;
                                    "></span>
                                    @error('password')
                                        <div style="color: red;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Forgot Password Link -->
                                <div class="form-group">
                                    <div style="float: right; margin-top: 20px; margin-bottom: 20px;">
                                        <a href="{{ url('admin-forgot-password') }}" style="font-size: small;">
                                            Forgot Password?
                                        </a>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group" style="margin-bottom: 0; margin-top: 1.25rem;">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                        Login
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
@endsection
