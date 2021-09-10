@extends('admin.layout.app')

@section('content')
    <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
        <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
            {{ Session::get('jwt_token') }}
            <h2 class="card-title font-weight-bold mb-1">Welcome to EmpManagment! 👋</h2>
            <p class="card-text mb-2">Please sign-in to your account and start the adventure</p>
            <div id="errorBox" style="color:red"></div>
            <form class="auth-login-form mt-2" action="{{ route('login-api') }}" method="POST" id="admin-login-form">
                <div class="form-group">
                    <label class="form-label" for="login-email">Email</label>
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
                            placeholder="············" aria-describedby="password" tabindex="2" />
                        <div class="input-group-append"><span class="input-group-text cursor-pointer"><i
                                    data-feather="eye"></i></span></div>
                    </div>
                    <span style="color:red;font-size: 13px" class="password-error error-box"></span>
                </div>
                <button class="btn btn-primary btn-block" tabindex="4">Sign in</button>
            </form>
            <p class="text-center mt-2"><span>New on our platform?</span><a
                    href="{{ route('admin-register') }}"><span>&nbsp;Create an account</span></a></p>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            const error = "{{ $error }}";
            if (error !== "") toastr.error(error);
        });

        $(document).on('submit', '#admin-login-form', function(e) {
            e.preventDefault();
            $('.is-invalid').removeClass('is-invalid');
            $('.error').html('');
            $.ajax({
                url: "{{ route('login-api') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    toastr['success']("Login successfully!!");
                    const result = response.results;
                    store_token_session(result.user_token, result.user);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    debugger;
                    e.preventDefault();
                    toastr['error'](jqXHR.responseJSON.message);
                    if (jqXHR.responseJSON.errors) {
                        for (const [key, value] of Object.entries(jqXHR.responseJSON.errors)) {
                            $(`.${key}-error`).html(value);
                            $(`#${key}`).addClass('is-invalid');
                        }
                    }
                }
            });
        });

        function store_token_session(user_token, user) {
            $.ajax({
                url: "{{ route('do_login') }}",
                method: 'post',
                data: {
                    '_token': "{{ csrf_token() }}",
                    user_token,
                    user
                },
                success: function(response) {
                    console.log(response);
                    if (response.success == true) {
                        setTimeout(window.location.reload(), 2000);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                }
            });
        }
    </script>
@endsection
