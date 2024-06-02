@extends('adminlte::page')

@section('title', 'Items')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Items</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Items</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="container-fluid mb-3">
            <div class="float-right">
                <a class="btn btn-secondary" href="item/create" role="button">Add
                Item</a>
            </div>
        </div>
        <div class="container">
            @foreach($items as $item)
                <div class="card mb-3">
                    <div class="card-header">
                        <strong>Category -> {{ $item->category->name }}</strong>
                    </div>
                    <div class="card-body">
                        @if($item->type == 'Image' && $item->image)
                            <div style="overflow: hidden; max-height: 400px; margin-bottom: 10px;">
                                <img src="{{ asset('image/' . $item->image) }}" class="img-fluid" alt="Image" style="max-height: 300px;">
                            </div>
                        @endif
                        @if($item->type == 'Pdf' && $item->pdf)
                            <a href="{{ asset('pdf/' . $item->pdf) }}" target="_blank">View PDF</a>
                        @endif
                        @if($item->type == 'Excel' && $item->excel)
                            <a href="{{ asset('excel/' . $item->excel) }}" target="_blank">View Excel</a>
                        @endif
                        <div class="text-container" style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;">
                            <p style="white-space: pre-wrap;">{!! $item->message !!}</p>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="delete btn btn-sm btn-danger" data-delete-id="{{ $item->id }}" data-token="{{ csrf_token() }}">Delete</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
@stop

@section('css')
    <style>
        .card-body img {
            max-height: 300px;
            object-fit: cover;
            width: 100%;
        }
        .text-container {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 10px;
            margin-top: 10px;
        }
    </style>
@stop

@section('js')
    <script>
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
                    $.ajax({
                        url: "item/" + id,
                        type: 'POST',
                        data: {
                            "id": id,
                            "_method": 'DELETE',
                            "_token": token
                        },
                        success: function (result) {
                            swal.fire("Deleted!", "The record is deleted.", "success");
                            location.reload();
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
