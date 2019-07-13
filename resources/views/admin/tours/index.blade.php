@extends('admin.layouts.master')

@section('title')
{{ $title }}
@endsection

@section('style')
<link href="{{ asset('themes/sb_admin_2/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('script')
<script src="{{ asset('themes/sb_admin_2/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('themes/sb_admin_2/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script>
    var url_delete = "{{route('admin.tours.destroy')}}";
    var table;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {

        table = $('#dataTable').dataTable({
            responsive: true,
            searching: false,
            processing: true,
            serverSide: true,
            "dom": 'rt<"#pagination"flp>',
            ajax: {
                "url": "{{route('admin.tours.index')}}",
                "data": function ( d ) {
                    // d.keyword = $('#s-keyword').val();
                },
                complete: function(){

                }
            },
            columns: [
            {data: 'id', "width": "5%"},
            {data: 'title', "width": "20%"},
            {data: 'type', "width": "10%"},
            {data: 'from_place', "width": "5%"},
            {data: 'to_place', "width": "10%"},
            {data: 'time_range', "width": "15%"},
            {data: 'is_hot', "width": "5%"},
            {data: 'is_coming', "width": "5%"},
            {data: 'action', "width": "25%"}
            ],
            "aoColumnDefs": [
                    // Column index begins at 0
                    { "sClass": "text-center", "aTargets": [ 7 ] },
                    { "sClass": "text-right", "aTargets": [ 8 ] }
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

    });
</script>
@endsection

@section('content')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>

{{ csrf_field() }}

<!-- DataTales -->
<div class="table-responsive">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>From</th>
                <th>To</th>
                <th>Time Range</th>
                <th>Is Hot</th>
                <th>Is Coming</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tfoot>
        </tfoot>
        <tbody>
        </tbody>
    </table>
</div>

@endsection
