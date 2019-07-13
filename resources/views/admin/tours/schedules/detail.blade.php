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

    var num = 4;
    function readImage() {
    if (window.File && window.FileList && window.FileReader) {
        var files = event.target.files; //FileList object
        var output = $(".preview-images-zone");

        for (let i = 0; i < files.length; i++) {
            var file = files[i];
            if (!file.type.match('image')) continue;

            var picReader = new FileReader();

            picReader.addEventListener('load', function (event) {
                var picFile = event.target;
                var html =  '<div class="preview-image preview-show-' + num + '">' +
                            '<div class="image-cancel" data-no="' + num + '">x</div>' +
                            '<div class="image-zone"><img id="pro-img-' + num + '" src="' + picFile.result + '"></div>' +
                            '<div class="tools-edit-image"><a href="javascript:void(0)" data-no="' + num + '" class="btn btn-light btn-edit-image">edit</a></div>' +
                            '</div>';

                output.append(html);
                num = num + 1;
            });

            picReader.readAsDataURL(file);
        }
    } else {
        console.log('Browser not support');
    }
}

  $(document).ready(function () {

        $('.summernote').summernote({
            height: 300,
        });

        document.getElementById('images').addEventListener('change', readImage, false);

    $( ".preview-images-zone" ).sortable();

    $(document).on('click', '.image-cancel', function() {
        let no = $(this).data('no');
        $(".preview-image.preview-show-"+no).remove();
    });
   })
})();
</script>
@endsection

@section('content')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
<form class="needs-validation" method="POST" action="{{ route('admin.tours.schedules.store', ['tour' => $tour_id]) }}" novalidate
    enctype="multipart/form-data">
    <input type="hidden" name="tour_id" value="{{$tour_id}}" />
    @if(isset($data->id))
    <input type="hidden" name="id" value="{{$data->id}}" />
    @endif
    {{ csrf_field() }}

    {{-- Short Title & Title --}}
    <div class="form-row">
        <div class="col-md-3 mb-3">
            <label for="short_title">{{ __('admin.form.tour_schedules.short_title.label') }}</label>
            <input type="text" class="form-control" id="short_title" name="short_title"
                placeholder="{{ __('admin.form.tour_schedules.short_title.place_holder') }}"
                value="@if(isset($data->short_title)){{$data->short_title}}@endif" required>
            <div class="invalid-feedback">
                {{ __('admin.form.tour_schedules.short_title.invalid') }}
            </div>
        </div>

        <div class="col-md-9 mb-3">
            <label for="title">{{ __('admin.form.tour_schedules.title.label') }}</label>
            <input type="text" class="form-control" id="title" name="title"
                placeholder="{{ __('admin.form.tour_schedules.title.place_holder') }}"
                value="@if(isset($data->title)){{$data->title}}@endif" required>
            <div class="invalid-feedback">
                {{ __('admin.form.tour_schedules.title.invalid') }}
            </div>
        </div>
    </div>

    {{-- Tour Schedule Content --}}
    <div class="form-row">
        <div class="col mb-3">
            <label for="content">{{ __('admin.form.tour_schedules.content.label') }}</label>
            <textarea class="summernote"
                name="content">@if(isset($data->content)){{$data->content}}@endif</textarea>
        </div>
    </div>

    {{-- Images --}}
    <div class="form-row">
        <div class="col mb-3">
            <fieldset class="form-group">
                <a href="javascript:void(0)" onclick="$('#images').click()">Upload Image</a>
                <input type="file" id="images" name="images[]" style="display: none;" class="form-control" multiple>
            </fieldset>
            <div class="preview-images-zone">
                @if(isset($data->images))
                @foreach($data->images as $image)
                <div class="preview-image preview-show-1">
                    <div class="image-cancel" data-no="{{$image->id}}">x</div>
                    <div class="image-zone">
                        <img id="pro-img-{{$image->id}}" src="{{asset('storage/' .$image->origin)}}">
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>

    <button class="btn btn-primary" type="submit">Save</button>
</form>

@endsection
