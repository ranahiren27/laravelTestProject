@extends('admin.layout.app')

@section('content')
    <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
        <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
            <h2 class="card-title font-weight-bold mb-1">Welcome to EmpManagment! 👋</h2>
            <div id="errorBox" style="color:red"></div>
            <div id="successBox" style="color:rgb(10, 232, 65)239)"></div>
            <form class="auth-login-form mt-2" action="{{ route('create-user-api') }}" method="POST"
                id="admin-register-form">
                <div class="form-group">
                    <label class="form-label" for="name">Name</label>
                    <input class="form-control" id="name" type="text" name="name" placeholder="john doe"
                        aria-describedby="login-name" autofocus="" tabindex="1" />
                    <span style="color:red;font-size: 13px" class="name-error error-box"></span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control" id="email" type="text" name="email" placeholder="john@example.com"
                        aria-describedby="login-email" autofocus="" tabindex="1" />
                    <span style="color:red;font-size: 13px" class="email-error error-box"></span>
                </div>
                <div class="form-group">
                    <div class="d-flex justify-content-between">
                        <label for="login-password">Password</label>
                    </div>
                    <div class="input-group input-group-merge form-password-toggle">
                        <input class="form-control form-control-merge" id="password" type="password" name="password"
                            placeholder="············" aria-describedby="login-password" tabindex="2" />
                        <div class="input-group-append"><span class="input-group-text cursor-pointer">
                                <i data-feather="eye"></i></span></div>
                    </div>
                    <span style="color:red;font-size: 13px" class="password-error error-box"></span>
                </div>
                <button class="btn btn-primary btn-block" tabindex="4">Sign Up</button>
            </form>
            <p class="text-center mt-2"><span>Already have account?</span>
                <a href="{{ route('admin-login') }}"><span>&nbsp;Login here</span></a>
            </p>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#admin-register-form").submit(function(event) {
                event.preventDefault();
                $('.error-box').html('');
                $('.form-control').removeClass('is-invalid');
                $.ajax({
                    url: '{{ route('register-api') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(data) {
                        toastr["success"]("You have been registered successfully.");
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.responseJSON.message !== "The name field is required." && !
                            jqXHR.responseJSON.errors)
                            toastr["error"](jqXHR.responseJSON.message);
                        if (jqXHR.responseJSON.errors) {
                            toastr["error"](jqXHR.responseJSON.message);
                            for (const [key, value] of Object.entries(jqXHR.responseJSON
                                    .errors)) {
                                $(`.${key}-error`).html(value);
                                $(`#${key}`).addClass('is-invalid');
                            }
                        }
                    }
                });
            });
        });
    </script>

@endsection
