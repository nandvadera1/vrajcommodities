@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Items</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Add New Item</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid mt-3">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Add New Item</h3>
            </div>
            {!! Form::open([$categories, 'url' => '/item', 'id' => 'validate', 'enctype' => 'multipart/form-data', 'class' => "form-horizontal", 'novalidate']) !!}
            @include('Admin.Item._form')
            <div class="card-footer">
                {!! Form::submit('Submit', ['class' => 'btn btn-secondary']) !!}
                <a href="/item" type="submit" class="btn btn-default float-right">Cancel</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop
