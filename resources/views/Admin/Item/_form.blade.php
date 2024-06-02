<div class="card-body">
    <div class="form-group row">
        {!! Form::label('category_id', 'Category', ['class' => 'col-sm-2 col-form-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('category_id', $categories, null, ['class' => $errors->has('type') ? 'form-control is-invalid' : 'form-control', 'required', 'placeholder' => "Select Category"]) !!}
        </div>
        @error('category_id')
        <p class="text-danger text-xs mt-1">
            {{ $message }}
        </p>
        @enderror
    </div>

    <div class="form-group row">
        {!! Form::label('type', 'Type', ['class' => 'col-sm-2 col-form-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('type', ['Image' => 'Image', 'Pdf' => 'Pdf', 'Excel' => 'Excel'], null, ['class' => $errors->has('type') ? 'form-control is-invalid' : 'form-control', 'required', 'placeholder' => "Select Type"]) !!}
            @error('type')
                <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('message', 'Message', ['class' => 'col-sm-2 col-form-label']) !!}
        <div class="col-sm-10">
            {!! Form::textarea('message', null, ['class' => $errors->has('message') ? 'form-control is-invalid' : 'form-control', 'id' => 'message-editor', 'placeholder' => "Enter Message (optional)"]) !!}
            @error('message')
                <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('pdf', 'PDF', ['class' => 'col-sm-2 col-form-label']) !!}
        <div class="col-sm-10">
            {!! Form::file('pdf', ['class' => $errors->has('pdf') ? 'form-control is-invalid' : 'form-control']) !!}
            @error('pdf')
                <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('excel', 'Excel', ['class' => 'col-sm-2 col-form-label']) !!}
        <div class="col-sm-10">
            {!! Form::file('excel', ['class' => $errors->has('excel') ? 'form-control is-invalid' : 'form-control']) !!}
            @error('excel')
                <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('image', 'Image', ['class' => 'col-sm-2 col-form-label']) !!}
        <div class="col-sm-10">
            {!! Form::file('image', ['class' => $errors->has('image') ? 'form-control is-invalid' : 'form-control']) !!}
            @error('image')
                <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('status', 'Status', ['class' => 'col-sm-2 col-form-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('status', ['Active' => 'Active', 'Inactive' => 'Inactive'], null, ['class' => $errors->has('status') ? 'form-control is-invalid' : 'form-control', 'required', 'placeholder' => "Select Status"]) !!}
            @error('status')
                <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<!-- CKEditor Initialization Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        ClassicEditor
            .create(document.querySelector('#message-editor'), {
                toolbar: [
                    'ckbox', 'heading', '|', 'undo', 'redo', '|', 'bold', 'italic', '|',
                    'blockQuote', 'indent', 'link', '|', 'bulletedList', 'numberedList'
                ],
            })
            .catch(error => {
                console.error(error);
            });
    });
</script>
