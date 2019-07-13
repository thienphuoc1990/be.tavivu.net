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
    var url_delete = "{{route('admin.contacts.delete')}}";
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
                "url": "{{route('admin.contacts.index')}}",
                "data": function ( d ) {
                    // d.keyword = $('#s-keyword').val();
                },
                complete: function(){

                }
            },
            columns: [
            {data: 'id'},
            {data: 'name'},
            {data: 'phone'},
            {data: 'email'},
            {data: 'address'},
            {data: 'action'}
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

    });



$("#dataTable").on("click", '.bt-delete', function(){
    var name = $(this).attr('data-name');
    var data = {
        ids: [$(this).attr('data-id')]
    };
    swal({
        title: "Cảnh Báo!",
        text: "Bạn có chắc muốn xóa <b>"+name+"</b> ?",
        html:true,
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Vâng, xóa!",
        closeOnConfirm: false
    },
    function(){
        $.ajax({
            url: url_delete,
            type: 'DELETE',
            data: data,
            dataType:'json',
            success: function(response) {
                if (response.success) {
                    swal({
                        title: "Thành công!",
                        text: "Liên hệ " + name + " đã bị xóa.",
                        html: true,
                        type: "success",
                        confirmButtonClass: "btn-primary",
                        confirmButtonText: "Đóng lại."
                    });
                } else {
                    errorHtml = '<ul class="text-left">';
                    $.each( response.errors, function( key, error ) {
                        errorHtml += '<li>' + error + '</li>';
                    });
                    errorHtml += '</ul>';
                    swal({
                        title: "Error! Refresh page and try again.",
                        text: errorHtml,
                        html: true,
                        type: "error",
                        confirmButtonClass: "btn-danger"
                    });
                }
                table.fnDraw();
            }
        });

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
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
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
