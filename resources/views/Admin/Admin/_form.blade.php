<div class="card-body">
    <div class="form-group row">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-2 col-form-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', $admin->name ?? null, ['class' => $errors->has('name') ? 'form-control is-invalid' : 'form-control', 'required', 'placeholder' => "Enter name"]) !!}
            @error('name')
                <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('email', 'Email', ['class' => 'col-sm-2 col-form-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('email', $admin->email ?? null, ['class' => $errors->has('email') ? 'form-control is-invalid' : 'form-control', 'required', 'placeholder' => "Enter Email"]) !!}
            @error('email')
                <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('password', 'Password', ['class' => 'col-sm-2 col-form-label']) !!}
        <div class="col-sm-10">
            {!! Form::password('password', ['class' => $errors->has('password') ? 'form-control is-invalid' : 'form-control', 'placeholder' => "Enter Password"]) !!}
            @error('password')
                <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('status', 'Status', ['class' => 'col-sm-2 col-form-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('status', ['Active' => 'Active', 'Inactive' => 'Inactive'], $admin->status ?? null, ['class' => $errors->has('status') ? 'form-control is-invalid' : 'form-control', 'required', 'placeholder' => "Select Status"]) !!}
            @error('status')
                <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

