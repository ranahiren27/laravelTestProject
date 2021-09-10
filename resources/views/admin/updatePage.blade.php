@extends('admin.layout.auth-layout')
@section('content')
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role Level</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="empoyer-data">
            </tbody>
        </table>
        <!-- Modal -->
    </div>
    <script>
        function deleteRecord(id) {
            $.ajax({
                url: '{{ route('delete-employer-api') }}',
                method: 'DELETE',
                data: {
                    id: id
                },
                success: function(data) {
                    
                    if (data.success) {
                        toastr["success"]("Employee deleted successfully!!");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.responseJSON.errors) {
                        for (const [key, value] of Object.entries(jqXHR.responseJSON.errors)) {
                            toastr["error"](value);
                        }
                    }
                }
            });
            getAllData();
        }

        function updateRecord(value, id) {
            $.ajax({
                url: '{{ route('update-role-api') }}',
                method: 'PUT',
                data: {
                    role: value,
                    id: id
                },
                success: function(data) {

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.responseJSON.errors) {
                        for (const [key, value] of Object.entries(jqXHR.responseJSON.errors)) {
                            toastr["error"](value);
                        }
                    }
                }
            });
            getAllData();
        }

        function getAllData() {
            $.ajax({
                url: "{{ route('get-all-employer-api') }}",
                method: 'GET',
                success: function(response) {
                    const data = response.results;
                    let html = '';
                    if (data.length > 0) {
                        for (let i = 0; i < data.length; i++) {
                            html +=
                                `<tr>
                                <td>
                                    <span class="font-weight-bold">${data[i].name}</span>
                                </td>
                                <td>${data[i].email}</td>
                                <td>
                                    <div class="demo-inline-spacing">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customRadio1-${data[i].id}" name="customRadio-${data[i].id}" class="custom-control-input"
                                            ${data[i].role==0?'checked=""':""} onchange="updateRecord(0,${data[i].id})">
                                            <label class="custom-control-label" for="customRadio1-${data[i].id}">Level One</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customRadio2-${data[i].id}" name="customRadio-${data[i].id}" class="custom-control-input" 
                                            ${data[i].role==1?'checked=""':""} onchange="updateRecord(1,${data[i].id})">
                                            <label class="custom-control-label" for="customRadio2-${data[i].id}">Level Two</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customRadio3-${data[i].id}" name="customRadio-${data[i].id}" class="custom-control-input" 
                                            ${data[i].role==2?'checked=""':""} onchange="updateRecord(2,${data[i].id})">
                                            <label class="custom-control-label" for="customRadio3-${data[i].id}">Level Three</label>
                                        </div>
                                    </div>
                                </td>
                            <td>
                                <div style="display:flex;">
                                    <button type="button" class="btn btn-outline-primary btn btn-danger waves-effect waves-float waves-light" data-toggle="modal" data-target="#confirmation-model-${data[i].id}">
                                        Delete
                                    </button>
                                    <div class="modal fade" id="confirmation-model-${data[i].id}" tabindex="-1" role="dialog"
                                        aria-labelledby="confirmation-model-${data[i].id}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body model-danger">
                                                    <p>
                                                        Are you sure you want to delete this item?
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" onclick="deleteRecord(${data[i].id})" class="btn btn-danger" data-dismiss="modal">Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>`;

                        }
                        $("#empoyer-data").html(html);
                    } else {
                        $('.table').html("You haven't added any empoyer yet!!");
                    }
                }
            });
        }
        $(document).ready(function() {
            getAllData();
        })
    </script>

@endsection
