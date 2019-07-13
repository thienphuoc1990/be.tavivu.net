@extends('admin.layouts.master')

@section('title')
{{ $title }}
@endsection

@section('style')
@endsection

@section('script')

<script>
    var url_delete = "{{route('admin.images.destroy')}}";
    var url_index = "{{route('admin.images.index')}}";
    var url_upload_image = "{{route('admin.images.upload')}}";
    var url_delete_image = "{{route('admin.images.destroy')}}";
    var assert_path = "{{asset('storage/')}}/";
    var table;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function getImages() {
        $.ajax({
            url: url_index,
            data: {
                "page": 1
            }
        })
        .done(function( data ) {
            $('.tavivu-gallery').html('');
            printImages(data.images);
        }); 
    }

    function printImages(images) {
        images.forEach(function(image, idx){
            $('.tavivu-gallery').append(htmlImage(image));
        });
    }

    function deleteImage(id) {
        $.ajax({
            url: url_delete_image,
            type: 'DELETE',
            data: {
                "id": id,
                "_token": "{{csrf_token()}}"
            }
        })
        .done(function( data ) {
            getImages();
        });
    }

    function htmlImage(image) {
        html = '';

        html += '<div class="col-md-2 col-xs-4 img-wrapper">';
        html += '<button type="button" class="btn btn-sm btn-danger" onclick="deleteImage('+image.id+')">Xóa</button>';
        html += '<img src="'+assert_path+image.thumb+'" class="img-fluid">';
        html += '</div>';

        return html;
    }

    function uploadImage() {
        var img = $('#uploadImageModel #origin')[0];
        var form_data = new FormData();
        form_data.append('file', img.files[0]);
        form_data.append('_token', '{{csrf_token()}}');
        form_data.append('title', $('#uploadImageModel #title').val());
        form_data.append('description', $('#uploadImageModel #description').val());
        
        $.ajax({
            url: url_upload_image,
            data: form_data,
            type: 'POST',
            contentType: false,
            processData: false,
            dataType: 'json'
        })
        .always(function() {
            $('#uploadImageModel').modal('hide');
            $('#uploadImageModel #title').val(null);
            $('#uploadImageModel #description').val(null);
            $('#uploadImageModel #origin').val(null);
            getImages();
        });
    }

    $(document).ready(function() {
        getImages();

        $('button[name="btn-upload-image"]').click(function() {
            uploadImage();
        });
    });
</script>
@endsection

@section('content')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>

{{ csrf_field() }}

<div class="upload-image">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadImageModel">Đăng hình mới</button>
</div>

<!-- Gallery -->
<div class="tavivu-gallery row"></div>

@include('admin.layouts.includes.upload_image_model')
@endsection