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
            {!! Form::select('type', ['Image' => 'Image', 'Pdf' => 'Pdf', 'Excel' => 'Excel', 'Message' => 'Message'], null, ['class' => $errors->has('type') ? 'form-control is-invalid' : 'form-control', 'required', 'placeholder' => "Select Type", 'id' => 'type-select']) !!}
            @error('type')
                <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group row conditional-field" id="message-field" style="display: none;">
        {!! Form::label('message', 'Message', ['class' => 'col-sm-2 col-form-label']) !!}
        <div class="col-sm-10">
            {!! Form::textarea('message', null, ['class' => $errors->has('message') ? 'form-control is-invalid' : 'form-control', 'id' => 'message-editor', 'placeholder' => "Enter Message (optional)"]) !!}
            @error('message')
                <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group row conditional-field" id="pdf-field" style="display: none;">
        {!! Form::label('pdf', 'PDF', ['class' => 'col-sm-2 col-form-label']) !!}
        <div class="col-sm-10">
            {!! Form::file('pdf', ['class' => $errors->has('pdf') ? 'form-control is-invalid' : 'form-control']) !!}
            @error('pdf')
                <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group row conditional-field" id="excel-field" style="display: none;">
        {!! Form::label('excel', 'Excel', ['class' => 'col-sm-2 col-form-label']) !!}
        <div class="col-sm-10">
            {!! Form::file('excel', ['class' => $errors->has('excel') ? 'form-control is-invalid' : 'form-control']) !!}
            @error('excel')
                <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group row conditional-field" id="image-field" style="display: none;">
        {!! Form::label('image', 'Image', ['class' => 'col-sm-2 col-form-label']) !!}
        <div class="col-sm-10">
            {!! Form::file('image', ['class' => $errors->has('image') ? 'form-control is-invalid' : 'form-control']) !!}
            @error('image')
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

            // Function to handle the display of conditional fields
            function handleTypeChange() {
                const type = document.querySelector('#type-select').value;
                const fields = document.querySelectorAll('.conditional-field');

                fields.forEach(field => field.style.display = 'none');

                if (type === 'Message') {
                    document.querySelector('#message-field').style.display = 'block';
                } else if (type === 'Pdf') {
                    document.querySelector('#pdf-field').style.display = 'block';
                } else if (type === 'Excel') {
                    document.querySelector('#excel-field').style.display = 'block';
                } else if (type === 'Image') {
                    document.querySelector('#message-field').style.display = 'block';
                    document.querySelector('#image-field').style.display = 'block';
                }
            }

            // Initial call to set the correct fields on page load
            handleTypeChange();

            // Add event listener to the type select box
            document.querySelector('#type-select').addEventListener('change', handleTypeChange);
    });
</script>
