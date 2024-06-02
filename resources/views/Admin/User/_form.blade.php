<div class="card-body">
    <div class="form-group row">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-2 col-form-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', $user->name ?? null, ['class' => $errors->has('name') ? 'form-control is-invalid' : 'form-control', 'required', 'placeholder' => "Enter name"]) !!}
            @error('name')
                <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('mobile', 'Mobile', ['class' => 'col-sm-2 col-form-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('mobile', $user->mobile ?? null, ['class' => $errors->has('mobile') ? 'form-control is-invalid' : 'form-control', 'required', 'placeholder' => "Enter Mobile"]) !!}
            @error('mobile')
                <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('device_id', 'Device Id', ['class' => 'col-sm-2 col-form-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('device_id', $user->device_id ?? null, ['class' => $errors->has('device_id') ? 'form-control is-invalid' : 'form-control', 'required', 'placeholder' => "Enter Device Id"]) !!}
            @error('device_id')
                <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('subcription_start', 'Subcription Start', ['class' => 'col-sm-2 col-form-label']) !!}
        <div class="col-sm-10">
            {!! Form::date('subcription_start', $user->subcription_start ?? null, ['class' => 'form-control', 'required']) !!}
        </div>
        @error('subcription_start')
        <p class="text-danger text-xs mt-1">
            {{ $message }}
        </p>
        @enderror
    </div>

    <div class="form-group row">
        {!! Form::label('subcription_end', 'Subcription End', ['class' => 'col-sm-2 col-form-label']) !!}
        <div class="col-sm-10">
            {!! Form::date('subcription_end', $user->subcription_end ?? null, ['class' => 'form-control', 'required']) !!}
        </div>
        @error('subcription_end')
        <p class="text-danger text-xs mt-1">
            {{ $message }}
        </p>
        @enderror
    </div>

    <div class="form-group row">
        {!! Form::label('status', 'Status', ['class' => 'col-sm-2 col-form-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('status', ['Active' => 'Active', 'Inactive' => 'Inactive'], $user->status ?? null, ['class' => $errors->has('status') ? 'form-control is-invalid' : 'form-control', 'required', 'placeholder' => "Select Status"]) !!}
            @error('status')
                <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

