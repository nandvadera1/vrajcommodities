@extends('adminlte::page')

@section('title', 'Admin')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Admin</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Admin</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Admin</h2>
                        <div class="float-right">
                            <a class="btn btn-secondary" href="admin/create" role="button">Add
                              Admin</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" bordered>
                        </x-adminlte-datatable>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        var mytable;

        /* DELETE Record using AJAX Requres */
        $(document).on('click', '.delete', function () {

            var id = $(this).data("delete-id");
            var token = $(this).data("token");

            swal.fire({
                title: "Are you sure?",
                text: "It will delete permanently.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#dc3545",
                confirmButtonText: "Confirm"
            }).then((result) => {
                if (result.value) {
                    $.ajax(
                        {
                            url: "admin/" + id,
                            type: 'POST',
                            data: {
                                "id": id,
                                "_method": 'DELETE',
                                "_token": token
                            },
                            success: function (result) {
                                swal.fire("Deleted!", "The record is deleted.", "success");
                                $('#table1').DataTable().draw();
                            },
                            error: function (request, status, error) {
                                var val = request.responseText;
                                alert("error" + val);
                            }
                        });
                } else {
                    swal.fire("Cancelled", "The record is safe", "error");
                }
            });
        });
    </script>
@stop

