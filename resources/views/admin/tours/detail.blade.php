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


  var url_delete = "{{route('admin.tours.destroy')}}";
    var table;
    var tableDetail;

    // Init select2
    var url_get_districts = '{{route("admin.tours.getPlaces")}}';
    $('select[name="from_place[]"]').select2({
        placeholder: '-- Chọn nơi đi --',
        ajax: {//---> Retrieve post data
            url: url_get_districts,
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

    $('select[name="to_place[]"]').select2({
        placeholder: '-- Chọn nơi đến --',
        ajax: {//---> Retrieve post data
            url: url_get_districts,
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
    function readBanner() {
    if (window.File && window.FileList && window.FileReader) {
        var files = event.target.files; //FileList object
        var output = $(".preview-banners-zone");

        for (let i = 0; i < files.length; i++) {
            var file = files[i];
            if (!file.type.match('image')) continue;

            var picReader = new FileReader();

            picReader.addEventListener('load', function (event) {
                var picFile = event.target;
                var html =  '<div class="preview-banner preview-show-' + num + '">' +
                            '<div class="banner-cancel" data-no="' + num + '">x</div>' +
                            '<div class="banner-zone"><img id="pro-img-' + num + '" src="' + picFile.result + '"></div>' +
                            '<div class="tools-edit-banner"><a href="javascript:void(0)" data-no="' + num + '" class="btn btn-light btn-edit-image">edit</a></div>' +
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
            height: 200,
        });

        document.getElementById('images').addEventListener('change', readImage, false);
        document.getElementById('banners').addEventListener('change', readBanner, false);

    $( ".preview-images-zone" ).sortable();
    $( ".preview-banners-zone" ).sortable();

    $(document).on('click', '.image-cancel', function() {
        let no = $(this).data('no');
        let delete_images = $('input[name="delete_images"]').val();
        delete_images = delete_images + ',' + no;
        $('input[name="delete_images"]').val(delete_images);
        $(".preview-image.preview-show-"+no).remove();
    });

$(document).on('click', '.banner-cancel', function() {
    let no = $(this).data('no');
    let delete_banners = $('input[name="delete_banners"]').val();
    delete_banners = delete_banners + ',' + no;
    $('input[name="delete_banners"]').val(delete_banners);
    $(".preview-banner.preview-show-"+no).remove();
});

    @if(isset($data->id))
    table = $('#dataTable').dataTable({
            responsive: true,
            searching: false,
            processing: true,
            serverSide: true,
            "dom": 'rt<"#pagination"flp>',
            ajax: {
                "url": "{{route('admin.tours.schedules.index', ['tour' => $data->id])}}",
                "data": function ( d ) {
                    // d.keyword = $('#s-keyword').val();
                },
                complete: function(){

                }
            },
            columns: [
            {data: 'id', "width": "10%"},
            {data: 'short_title', "width": "20%"},
            {data: 'title', "width": "50%"},
            {data: 'action', "width": "20%"}
            ],
            "aoColumnDefs": [
                    // Column index begins at 0
                    { "sClass": "text-center", "aTargets": [ 2 ] },
                    { "sClass": "text-right", "aTargets": [ 3 ] }
                    ],
                    "language": {
                        "decimal": "",
                        "emptyTable": "Không có dữ liệu hợp lệ",
                        "info": "Hiển thị từ _START_ đến _END_ / _TOTAL_ kết quả",
                        "infoEmpty": "Hiển thị từ 0 đến 0 trên 0 dòng",
                        "infoFiltered": "(filtered from _MAX_ total entries)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Hiển thị _MENU_ kết quả",
                        "loadingRecords": "Đang tải...",
                        "processing": "Đang xử lý...",
                        "search": "Search:",
                        "zeroRecords": "Không có kết quả nào được tìm thấy",
                        "paginate": {
                            "first": "Đầu",
                            "last": "Cuối",
                            "next": "Tiếp",
                            "previous": "Trước"
                        },
                        "aria": {
                            "sortAscending": ": activate to sort column ascending",
                            "sortDescending": ": activate to sort column descending"
                        }
                    }

                });

    tableDetail = $('#dataTableDetail').dataTable({
            responsive: true,
            searching: false,
            processing: true,
            serverSide: true,
            "dom": 'rt<"#pagination"flp>',
            ajax: {
                "url": "{{route('admin.tours.details.index', ['tour' => $data->id])}}",
                "data": function ( d ) {
                    // d.keyword = $('#s-keyword').val();
                },
                complete: function(){

                }
            },
            columns: [
            {data: 'id', "width": "10%"},
            {data: 'start_date', "width": "15%"},
            {data: 'flight_in', "width": "25%"},
            {data: 'flight_out', "width": "25%"},
            {data: 'active', "width": "10%"},
            {data: 'action', "width": "15%"}
            ],
            "aoColumnDefs": [
                    // Column index begins at 0
                    { "sClass": "text-center", "aTargets": [ 4 ] },
                    { "sClass": "text-right", "aTargets": [ 5 ] }
                    ],
                    "language": {
                        "decimal": "",
                        "emptyTable": "Không có dữ liệu hợp lệ",
                        "info": "Hiển thị từ _START_ đến _END_ / _TOTAL_ kết quả",
                        "infoEmpty": "Hiển thị từ 0 đến 0 trên 0 dòng",
                        "infoFiltered": "(filtered from _MAX_ total entries)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Hiển thị _MENU_ kết quả",
                        "loadingRecords": "Đang tải...",
                        "processing": "Đang xử lý...",
                        "search": "Search:",
                        "zeroRecords": "Không có kết quả nào được tìm thấy",
                        "paginate": {
                            "first": "Đầu",
                            "last": "Cuối",
                            "next": "Tiếp",
                            "previous": "Trước"
                        },
                        "aria": {
                            "sortAscending": ": activate to sort column ascending",
                            "sortDescending": ": activate to sort column descending"
                        }
                    }

                });
                @endif
   });

})();
</script>
@endsection

@section('content')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
<form class="needs-validation" method="POST" action="{{ route('admin.tours.store') }}" novalidate
    enctype="multipart/form-data">
    @if(isset($data->id))
    <input type="hidden" name="id" value="{{$data->id}}" />
    @endif
    <input type="hidden" name="delete_images" value="" />
    <input type="hidden" name="delete_banners" value="" />
    {{ csrf_field() }}

    {{-- Title & Sub title --}}
    <div class="form-row">
        <div class="col-md-6 mb-3">
            <label for="title">{{ __('admin.form.tours.title.label') }}</label>
            <input type="text" class="form-control" id="title" name="title"
                placeholder="{{ __('admin.form.tours.title.place_holder') }}"
                value="@if(isset($data->title)){{$data->title}}@endif" required>
            <div class="invalid-feedback">
                {{ __('admin.form.tours.title.invalid') }}
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label for="sub_title">{{ __('admin.form.tours.sub_title.label') }}</label>
            <input type="text" class="form-control" id="sub_title" name="sub_title"
                placeholder="{{ __('admin.form.tours.sub_title.place_holder') }}"
                value="@if(isset($data->sub_title)){{$data->sub_title}}@endif">
        </div>
    </div>

    {{-- Time range & vehicle & tour type --}}
    <div class="form-row">
        <div class="col-md-4 mb-3">
            <label for="time_range">{{ __('admin.form.tours.time_range.label') }}</label>
            <input type="text" class="form-control" id="time_range" name="time_range"
                placeholder="{{ __('admin.form.tours.time_range.place_holder') }}"
                value="@if(isset($data->time_range)){{$data->time_range}}@endif" required>
            <div class="invalid-feedback">
                {{ __('admin.form.tours.time_range.invalid') }}
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <label for="vehicle">{{ __('admin.form.tours.vehicle.label') }}</label>
            <input type="text" class="form-control" id="vehicle" name="vehicle"
                placeholder="{{ __('admin.form.tours.vehicle.place_holder') }}"
                value="@if(isset($data->vehicle)){{$data->vehicle}}@endif">
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

    {{-- Form Place & To Place --}}
    <div class="form-row">
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label for="from_place">{{ __('admin.form.tours.from_place.label') }}</label>
                <select class="custom-select" id="from_place" name="from_place[]"
                    placeholder="{{ __('admin.form.tours.from_place.place_holder') }}" required multiple="multiple">
                    <option value="0">{{ __('admin.form.tours.from_place.place_holder') }}</option>
                    @if(isset($data->from_place))
                    @foreach($data->from_place as $item)
                        <option value="{{$item->id}}" selected>{{$item->title}}</option>
                    @endforeach
                    @endif
                </select>
                <div class="invalid-feedback">{{ __('admin.form.tours.from_place.invalid') }}</div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label for="to_place">{{ __('admin.form.tours.to_place.label') }}</label>
                <select class="custom-select" id="to_place" name="to_place[]"
                    placeholder="{{ __('admin.form.tours.to_place.place_holder') }}" required multiple="multiple">
                    <option value="0">{{ __('admin.form.tours.to_place.place_holder') }}</option>
                    @if(isset($data->to_place))
                    @foreach($data->to_place as $item)
                        <option value="{{$item->id}}" selected>{{$item->title}}</option>
                    @endforeach
                    @endif
                </select>
                <div class="invalid-feedback">{{ __('admin.form.tours.to_place.invalid') }}</div>
            </div>
        </div>
    </div>

    {{-- Tour Attractions --}}
    <div class="form-row">
        <div class="col mb-3">
            <label for="tour_attractions">{{ __('admin.form.tours.tour_attractions.label') }}</label>
            <textarea class="summernote"
                name="tour_attractions">@if(isset($data->tour_attractions)){{$data->tour_attractions}}@endif</textarea>
        </div>
    </div>

    {{-- Tour Policies --}}
    <div class="form-row">
        <div class="col mb-3">
            <label for="tour_policies">{{ __('admin.form.tours.tour_policies.label') }}</label>
            <textarea class="summernote"
                name="tour_policies">@if(isset($data->tour_policies)){{$data->tour_policies}}@endif</textarea>
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
                @if(isset($data->images_rel))
                @foreach($data->images_rel as $image)
                <div class="preview-image preview-show-{{$image->id}}">
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

    {{-- Banners --}}
    <div class="form-row">
        <div class="col mb-3">
            <fieldset class="form-group">
                <a href="javascript:void(0)" onclick="$('#banners').click()">Upload Banners</a>
                <input type="file" id="banners" name="banners[]" style="display: none;" class="form-control" multiple>
            </fieldset>
            <div class="preview-banners-zone">
                @if(isset($data->banners_rel))
                @foreach($data->banners_rel as $banner)
                <div class="preview-banner preview-show-{{$banner->id}}">
                    <div class="banner-cancel" data-no="{{$banner->id}}">x</div>
                    <div class="banner-zone">
                        <img id="pro-img-{{$banner->id}}" src="{{asset('storage/' .$banner->origin)}}">
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>

    {{-- Is Hot & Is Coming --}}
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
            <div class="form-group">
                <label for="is_coming">{{ __('admin.form.global.is_coming.label') }}</label>
                <select class="custom-select" id="is_coming" name="is_coming"
                    placeholder="{{ __('admin.form.global.is_coming.place_holder') }}" required>
                    {!! $is_coming_options !!}
                </select>
            </div>
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

    @if(isset($data->id))
    {{-- Tour Schedules --}}
    <div class="form-row">
        <div class="col mb-3">
            <label for="tour_schedules">{{ __('admin.form.tours.tour_schedules.label') }}</label>
            <div class="text-right">
                <a href="{{route('admin.tours.schedules.add', ['tour' => $data->id])}}"
                    class="btn btn-sm btn-primary"><i class="fa fa-plus"></i>
                    {{ __('admin.label.tours.add_schedule') }}</a>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Short Name</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tfoot>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tour Details --}}
    <div class="form-row">
        <div class="col mb-3">
            <label for="tour_details">{{ __('admin.form.tours.tour_details.label') }}</label>
            <div class="text-right">
                <a href="{{route('admin.tours.details.add', ['tour' => $data->id])}}"
                    class="btn btn-sm btn-primary"><i class="fa fa-plus"></i>
                    {{ __('admin.label.tours.add_detail') }}</a>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTableDetail" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Start date</th>
                            <th>Flight In</th>
                            <th>Flight Out</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tfoot>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif


    <button class="btn btn-primary" type="submit">Save</button>
</form>

@endsection
