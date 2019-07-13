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
})();
</script>
@endsection

@section('content')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
<form class="needs-validation" method="POST" action="{{ route('admin.contacts.store') }}" novalidate
    enctype="multipart/form-data">
    @if(isset($data->id))
    <input type="hidden" name="id" value="{{$data->id}}" />
    @endif
    {{ csrf_field() }}

    {{-- name --}}
    <div class="form-row">
        <div class="col mb-3">
            <label for="name">{{ __('admin.form.contacts.name.label') }}</label>
            <input type="text" class="form-control" id="name" name="name"
                placeholder="{{ __('admin.form.contacts.name.place_holder') }}"
                value="@if(isset($data->name)){{$data->name}}@endif" required>
            <div class="invalid-feedback">
                {{ __('admin.form.contacts.name.invalid') }}
            </div>
        </div>
    </div>

    {{-- phone --}}
    <div class="form-row">
        <div class="col mb-3">
            <label for="phone">{{ __('admin.form.contacts.phone.label') }}</label>
            <input type="text" class="form-control" id="phone" name="phone"
                placeholder="{{ __('admin.form.contacts.phone.place_holder') }}"
                value="@if(isset($data->phone)){{$data->phone}}@endif" required>
            <div class="invalid-feedback">
                {{ __('admin.form.contacts.phone.invalid') }}
            </div>
        </div>
    </div>

    {{-- email --}}
    <div class="form-row">
        <div class="col mb-3">
            <label for="email">{{ __('admin.form.contacts.email.label') }}</label>
            <input type="text" class="form-control" id="email" name="email"
                placeholder="{{ __('admin.form.contacts.email.place_holder') }}"
                value="@if(isset($data->email)){{$data->email}}@endif" required>
            <div class="invalid-feedback">
                {{ __('admin.form.contacts.email.invalid') }}
            </div>
        </div>
    </div>

    {{-- address --}}
    <div class="form-row">
        <div class="col mb-3">
            <label for="address">{{ __('admin.form.contacts.address.label') }}</label>
            <input type="text" class="form-control" id="address" name="address"
                placeholder="{{ __('admin.form.contacts.address.place_holder') }}"
                value="@if(isset($data->address)){{$data->address}}@endif" required>
            <div class="invalid-feedback">
                {{ __('admin.form.contacts.address.invalid') }}
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="form-row">
        <div class="col mb-3">
            <label for="content">{{ __('admin.form.contacts.content.label') }}</label>
            <textarea name="content" class="form-control" rows="5">@if(isset($data->content)){{$data->content}}@endif</textarea>
        </div>
    </div>

    <button class="btn btn-primary" type="submit">Save</button>
</form>

@endsection
