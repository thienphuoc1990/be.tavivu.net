@extends('admin.layouts.master')

@section('title')
{{ $title }}
@endsection

@section('style')
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="{{ asset('themes/sb_admin_2/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('script')
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset('themes/sb_admin_2/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('themes/sb_admin_2/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
    (function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);

  $(document).ready(function () {
    $('.summernote').summernote({
        height: 200,
    });

    	$(document).on('change', '.btn-file :file', function() {
		var input = $(this),
			label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [label]);
		});

		$('.btn-file :file').on('fileselect', function(event, label) {

		    var input = $(this).parents('.input-group').find(':text'),
		        log = label;

		    if( input.length ) {
		        input.val(log);
		    } else {
		        if( log ) alert(log);
		    }

		});
		function readURL(input) {
		    if (input.files && input.files[0]) {
		        var reader = new FileReader();

		        reader.onload = function (e) {
		            $('#img-upload').attr('src', e.target.result);
		        }

		        reader.readAsDataURL(input.files[0]);
		    }
		}

		$("#imgInp").change(function(){
		    readURL(this);
		});
   });

})();
</script>
@endsection

@section('content')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
<form class="needs-validation" method="POST" action="{{ route('admin.places.store') }}" novalidate
    enctype="multipart/form-data">
    @if(isset($data->id))
    <input type="hidden" name="id" value="{{$data->id}}" />
    @endif
    {{ csrf_field() }}

    {{-- Title & Type --}}
    <div class="form-row">
        <div class="col mb-8">
            <label for="title">{{ __('admin.form.places.title.label') }}</label>
            <input type="text" class="form-control" id="title" name="title"
                placeholder="{{ __('admin.form.places.title.place_holder') }}"
                value="@if(isset($data->title)){{$data->title}}@endif" required>
            <div class="invalid-feedback">
                {{ __('admin.form.places.title.invalid') }}
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="form-group">
                <label for="type">{{ __('admin.form.tours.type.label') }}</label>
                <select class="custom-select" id="type" name="type"
                    placeholder="{{ __('admin.form.tours.type.place_holder') }}" required>
                    {!! $type_options !!}
                </select>
            </div>
        </div>
    </div>

    {{-- Lat & Long --}}
    <div class="form-row">
        <div class="col-md-6 mb-3">
            <label for="latitude">{{ __('admin.form.places.latitude.label') }}</label>
            <input type="text" class="form-control" id="latitude" name="latitude"
                placeholder="{{ __('admin.form.places.latitude.place_holder') }}"
                value="@if(isset($data->latitude)){{$data->latitude}}@endif">
        </div>
        <div class="col-md-6 mb-3">
            <label for="longitude">{{ __('admin.form.places.longitude.label') }}</label>
            <input type="text" class="form-control" id="longitude" name="longitude"
                placeholder="{{ __('admin.form.places.longitude.place_holder') }}"
                value="@if(isset($data->longitude)){{$data->longitude}}@endif">
        </div>
    </div>

    {{-- Description --}}
    <div class="form-row">
        <div class="col mb-3">
            <label for="description">{{ __('admin.form.places.description.label') }}</label>
            <textarea class="summernote"
                name="description">@if(isset($data->description)){{$data->description}}@endif</textarea>
        </div>
    </div>

    {{-- Attractions --}}
    <div class="form-row">
        <div class="col mb-3">
            <label for="attractions">{{ __('admin.form.places.attractions.label') }}</label>
            <textarea class="summernote"
                name="attractions">@if(isset($data->attractions)){{$data->attractions}}@endif</textarea>
        </div>
    </div>

    {{-- Is Hot & Index --}}
    <div class="form-row">
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label for="is_hot">{{ __('admin.form.global.is_hot.label') }}</label>
                <select class="custom-select" id="is_hot" name="is_hot"
                    placeholder="{{ __('admin.form.global.is_hot.place_holder') }}" required>
                    {!! $is_hot_options !!}
                </select>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="index">{{ __('admin.form.global.index.label') }}</label>
            <input type="text" class="form-control" id="index" name="index"
                placeholder="{{ __('admin.form.global.index.place_holder') }}"
                value="@if(isset($data->index)){{$data->index}}@endif">
        </div>
    </div>

    {{-- Image --}}
    <div class="form-row form-image">
        <div class="col-md-6 mb-3">
            <label for="image">{{ __('admin.form.places.image.label') }}</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text btn-file" id="basic-addon1">Browse… <input type="file" name="image"
                            id="imgInp"></span>
                </div>
                <input type="text" class="form-control" placeholder="{{ __('admin.form.places.image.place_holder') }}"
                    readonly>
            </div>
        </div>
        <div class="col-md-6"></div>
        <div class="col-md-3 mb-3">
            <img id='img-upload' src="@if(isset($data->image)) {{asset('storage/' .$data->image)}} @endif" />
        </div>
    </div>

    <button class="btn btn-primary" type="submit">Save</button>
</form>

@endsection
