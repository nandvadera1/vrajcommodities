@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Categories</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Edit Category</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid mt-3">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Edit Category</h3>
            </div>
            {!! Form::model($category, ['url' => '/category/' . $category->id , 'id' => 'validate', 'method'=>'patch', 'enctype' => 'multipart/form-data', 'novalidate']) !!}
            @include('Admin.Category._form')
            <div class="card-footer">
                {!! Form::submit('Update', ['class' => 'btn btn-secondary']) !!}
                <a href="/category" type="submit" class="btn btn-default float-right">Cancel</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop
