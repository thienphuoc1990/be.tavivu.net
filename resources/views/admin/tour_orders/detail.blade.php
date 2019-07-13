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



    // Init select2
    var url_get_tours = '{{route("admin.tours.getTours")}}';
    var url_get_tour_details = '{{route("admin.tours.getTourDetails")}}';
    $('select[name="tour_id"]').select2({
        placeholder: '-- Chọn tour --',
        ajax: {//---> Retrieve post data
            url: url_get_tours,
            dataType: 'json',
            delay: 250, //---> Delay in ms while typing when to perform a AJAX search
            data: function (params) {
                return {
                    q: params.term, //---> Search query
                    action: 'mishagetposts', // AJAX action for admin-ajax.php
                };
            },
            processResults: function( data ) {
                return {
                    results: data
                };
            },
            cache: true,
        }
    });

    $('select[name="tour_detail_id"]').select2({
        placeholder: '-- Chọn ngày khởi hành --',
        ajax: {//---> Retrieve post data
            url: url_get_tour_details,
            dataType: 'json',
            delay: 250, //---> Delay in ms while typing when to perform a AJAX search
            data: function (params) {
                return {
                    q: params.term, //---> Search query
                    tour_id: $('select[name="tour_id"]').val(),
                    action: 'mishagetposts', // AJAX action for admin-ajax.php
                };
            },
            processResults: function( data ) {
                return {
                    results: data
                };
            },
            cache: true,
        }
    });


})();
</script>
@endsection

@section('content')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
<form class="needs-validation" method="POST" action="{{ route('admin.tour_orders.store') }}" novalidate
    enctype="multipart/form-data">
    @if(isset($data->id))
    <input type="hidden" name="id" value="{{$data->id}}" />
    @endif
    {{ csrf_field() }}

    {{-- Tour & Tour Detail --}}
    <div class="form-row">
        <div class="col-md-6 mb-3">
            <label for="tour_id">{{ __('admin.form.tour_orders.tour_id.label') }}</label>
            <select class="custom-select" id="tour_id" name="tour_id"
                placeholder="{{ __('admin.form.tour_orders.tour_id.place_holder') }}" required>
                <option value="0">{{ __('admin.form.tour_orders.tour_id.place_holder') }}</option>
                @if(isset($data->tour))
                <option value="{{$data->tour->id}}" selected>{{$data->tour->title}}</option>
                @endif
            </select>
            <div class="invalid-feedback">{{ __('admin.form.tour_orders.tour_id.invalid') }}</div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="tour_detail_id">{{ __('admin.form.tour_orders.tour_detail_id.label') }}</label>
            <select class="custom-select" id="tour_detail_id" name="tour_detail_id"
                placeholder="{{ __('admin.form.tour_orders.tour_detail_id.place_holder') }}" required>
                <option value="0">{{ __('admin.form.tour_orders.tour_detail_id.place_holder') }}</option>
                @if(isset($data->tour_detail))
                <option value="{{$data->tour_detail->id}}" selected>{{$data->tour_detail->start_date}}</option>
                @endif
            </select>
            <div class="invalid-feedback">{{ __('admin.form.tour_orders.tour_detail_id.invalid') }}</div>
        </div>
    </div>

    {{-- tickets --}}
    <div class="form-row">
        <div class="col-md-4 mb-3">
            <label for="tickets">{{ __('admin.form.tour_orders.tickets.label') }}</label>
            <input type="number" min="0" class="form-control" id="tickets" name="tickets"
                placeholder="{{ __('admin.form.tour_orders.tickets.place_holder') }}"
                value="@if(isset($data->tickets)){{$data->tickets}}@endif" required>
            <div class="invalid-feedback">
                {{ __('admin.form.tour_orders.tickets.invalid') }}
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label for="kid_tickets">{{ __('admin.form.tour_orders.kid_tickets.label') }}</label>
            <input type="number" min="0" class="form-control" id="kid_tickets" name="kid_tickets"
                placeholder="{{ __('admin.form.tour_orders.kid_tickets.place_holder') }}"
                value="@if(isset($data->kid_tickets)){{$data->kid_tickets}}@endif" required>
            <div class="invalid-feedback">
                {{ __('admin.form.tour_orders.kid_tickets.invalid') }}
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label for="baby_tickets">{{ __('admin.form.tour_orders.baby_tickets.label') }}</label>
            <input type="number" min="0" class="form-control" id="baby_tickets" name="baby_tickets"
                placeholder="{{ __('admin.form.tour_orders.baby_tickets.place_holder') }}"
                value="@if(isset($data->baby_tickets)){{$data->baby_tickets}}@endif" required>
            <div class="invalid-feedback">
                {{ __('admin.form.tour_orders.baby_tickets.invalid') }}
            </div>
        </div>
    </div>

    {{-- name --}}
    <div class="form-row">
        <div class="col mb-3">
            <label for="name">{{ __('admin.form.tour_orders.name.label') }}</label>
            <input type="text" class="form-control" id="name" name="name"
                placeholder="{{ __('admin.form.tour_orders.name.place_holder') }}"
                value="@if(isset($data->name)){{$data->name}}@endif" required>
            <div class="invalid-feedback">
                {{ __('admin.form.tour_orders.name.invalid') }}
            </div>
        </div>
    </div>

    {{-- phone --}}
    <div class="form-row">
        <div class="col mb-3">
            <label for="phone">{{ __('admin.form.tour_orders.phone.label') }}</label>
            <input type="text" class="form-control" id="phone" name="phone"
                placeholder="{{ __('admin.form.tour_orders.phone.place_holder') }}"
                value="@if(isset($data->phone)){{$data->phone}}@endif" required>
            <div class="invalid-feedback">
                {{ __('admin.form.tour_orders.phone.invalid') }}
            </div>
        </div>
    </div>

    {{-- email --}}
    <div class="form-row">
        <div class="col mb-3">
            <label for="email">{{ __('admin.form.tour_orders.email.label') }}</label>
            <input type="text" class="form-control" id="email" name="email"
                placeholder="{{ __('admin.form.tour_orders.email.place_holder') }}"
                value="@if(isset($data->email)){{$data->email}}@endif" required>
            <div class="invalid-feedback">
                {{ __('admin.form.tour_orders.email.invalid') }}
            </div>
        </div>
    </div>

    {{-- address --}}
    <div class="form-row">
        <div class="col mb-3">
            <label for="address">{{ __('admin.form.tour_orders.address.label') }}</label>
            <input type="text" class="form-control" id="address" name="address"
                placeholder="{{ __('admin.form.tour_orders.address.place_holder') }}"
                value="@if(isset($data->address)){{$data->address}}@endif" required>
            <div class="invalid-feedback">
                {{ __('admin.form.tour_orders.address.invalid') }}
            </div>
        </div>
    </div>

    {{-- Note --}}
    <div class="form-row">
        <div class="col mb-3">
            <label for="notes">{{ __('admin.form.tour_orders.notes.label') }}</label>
            <textarea name="notes" class="form-control"
                rows="5">@if(isset($data->notes)){{$data->notes}}@endif</textarea>
        </div>
    </div>

    {{-- Status --}}
    <div class="form-row">
        <div class="col mb-3">
            <label for="status">{{ __('admin.form.global.status.label') }}</label>
            <select class="custom-select" id="status" name="status"
                placeholder="{{ __('admin.form.global.status.place_holder') }}" required>
                {!! $status_options !!}
            </select>
        </div>
    </div>

    <button class="btn btn-primary" type="submit">Save</button>
</form>

@endsection
