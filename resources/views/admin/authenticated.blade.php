@extends('admin.layout.auth-layout')

@section('content')
    <!-- Basic Horizontal form layout section start -->
    <section id="basic-horizontal-layouts">
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add new employee</h4>
                    </div>
                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('add-employer-api') }}" method="post"
                            id="add-employee-form">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group row">
                                        <div class="col-sm-3 col-form-label">
                                            <label for="name" class="required">First Name</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" id="name" class="form-control" name="name"
                                                placeholder="Name">
                                            <span style="color:red;font-size: 13px" class="name-error error-box"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <div class="col-sm-3 col-form-label">
                                            <label for="email">Email</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" id="email" class="form-control" name="email"
                                                placeholder="Email">
                                            <span style="color:red;font-size: 13px" class="email-error error-box"></span>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="jwt" value={{ $jwt }} />
                                <div class="col-12">
                                    <div class="form-group row">
                                        <div class="col-sm-3 col-form-label">
                                            <label for="password">Password</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="password" id="password" class="form-control" name="password"
                                                placeholder="Password">
                                            <span style="color:red;font-size: 13px" class="password-error error-box"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-9 offset-sm-3">

                                    <div id="errorBox" style="color:red"></div>
                                    <div id="successBox" style="color:rgb(48, 209, 75)"></div>
                                    <button type="submit"
                                        class="btn btn-primary mr-1 waves-effect waves-float waves-light">Add
                                        Employee</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Basic Horizontal form layout section end -->
    <script>
        $(document).ready(function() {
            $("#add-employee-form").submit(function(event) {
                event.preventDefault();
                $('.error-box').html('');
                $('.form-control').removeClass('is-invalid');
                $.ajax({
                    url: '{{ route('add-employer-api') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(data) {
                        debugger;
                        toastr["success"]("new employee added successfully.");
                        $(this).closest('form').find("input[type=text], textarea").val("");
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
