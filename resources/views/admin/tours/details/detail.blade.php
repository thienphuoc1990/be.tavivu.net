@extends('admin.layouts.master')

@section('title')
{{ $title }}
@endsection

@section('style')
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

@section('script')
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
        $('#start_date').datepicker({
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            // minDate: today,
            // maxDate: function () {
                // return $('#end_date').val();
            // }
        });
   });

})();
</script>
@endsection

@section('content')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
<form class="needs-validation" method="POST" action="{{ route('admin.tours.details.store', ['tour' => $tour_id]) }}" novalidate
    enctype="multipart/form-data">
    <input type="hidden" name="tour_id" value="{{$tour_id}}" />
    @if(isset($data->id))
    <input type="hidden" name="id" value="{{$data->id}}" />
    @endif
    {{ csrf_field() }}

    {{-- Start date --}}
    <div class="form-row">
        <div class="col mb-3">
            <label for="start_date">{{ __('admin.form.tours.start_date.label') }}</label>
            <input type="text" class="form-control" id="start_date" name="start_date"
                placeholder="{{ __('admin.form.tours.start_date.place_holder') }}"
                value="@if(isset($data->start_date)){{$data->start_date}}@endif" required>
            <div class="invalid-feedback">
                {{ __('admin.form.tours.start_date.invalid') }}
            </div>
        </div>
    </div>

    {{-- Flight in & flight out --}}
    <div class="form-row">
        <div class="col-md-6 mb-3">
            <label for="flight_in">{{ __('admin.form.tour_details.flight_in.label') }}</label>
            <input type="text" class="form-control" id="flight_in" name="flight_in"
                placeholder="{{ __('admin.form.tour_details.flight_in.place_holder') }}"
                value="@if(isset($data->flight_in)){{$data->flight_in}}@endif" required>
            <div class="invalid-feedback">
                {{ __('admin.form.tour_details.flight_in.invalid') }}
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label for="flight_out">{{ __('admin.form.tour_details.flight_out.label') }}</label>
            <input type="text" class="form-control" id="flight_out" name="flight_out"
                placeholder="{{ __('admin.form.tour_details.flight_out.place_holder') }}"
                value="@if(isset($data->flight_out)){{$data->flight_out}}@endif" required>
            <div class="invalid-feedback">
                {{ __('admin.form.tour_details.flight_out.invalid') }}
            </div>
        </div>
    </div>

    {{-- Prices --}}
    <div class="form-row">
        <div class="col-md-3 col-ms-6 mb-3">
            <label for="price">{{ __('admin.form.tours.price.label') }}</label>
            <input type="text" class="form-control" id="price" name="price"
                placeholder="{{ __('admin.form.tours.price.place_holder') }}"
                value="@if(isset($data->price)){{$data->price}}@endif" required>
            <div class="invalid-feedback">
                {{ __('admin.form.tours.price.invalid') }}
            </div>
        </div>
        <div class="col-md-3 col-ms-6 mb-3">
            <label for="kid_price">{{ __('admin.form.tours.kid_price.label') }}</label>
            <input type="text" class="form-control" id="kid_price" name="kid_price"
                placeholder="{{ __('admin.form.tours.kid_price.place_holder') }}"
                value="@if(isset($data->kid_price)){{$data->kid_price}}@endif">
        </div>
        <div class="col-md-3 col-ms-6 mb-3">
            <label for="baby_price">{{ __('admin.form.tours.baby_price.label') }}</label>
            <input type="text" class="form-control" id="baby_price" name="baby_price"
                placeholder="{{ __('admin.form.tours.baby_price.place_holder') }}"
                value="@if(isset($data->baby_price)){{$data->baby_price}}@endif">
        </div>
        <div class="col-md-3 col-ms-6 mb-3">
            <label for="single_room_price">{{ __('admin.form.tours.single_room_price.label') }}</label>
            <input type="text" class="form-control" id="single_room_price" name="single_room_price"
                placeholder="{{ __('admin.form.tours.single_room_price.place_holder') }}"
                value="@if(isset($data->single_room_price)){{$data->single_room_price}}@endif">
        </div>
    </div>

    {{-- Index & Active --}}
    <div class="form-row">
        <div class="col-md-6 mb-3">
            <label for="index">{{ __('admin.form.global.index.label') }}</label>
            <input type="text" class="form-control" id="index" name="index"
                placeholder="{{ __('admin.form.global.index.place_holder') }}"
                value="@if(isset($data->index)){{$data->index}}@endif">
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label for="active">{{ __('admin.form.global.active.label') }}</label>
                <select class="custom-select" id="active" name="active"
                    placeholder="{{ __('admin.form.global.active.place_holder') }}" required>
                    {!! $is_active_options !!}
                </select>
            </div>
        </div>
    </div>

    <button class="btn btn-primary" type="submit" name="action" value="save">Save</button>
    <button class="btn btn-primary" type="submit" name="action" value="save_continue">Save & Continue</button>
</form>

@endsection
