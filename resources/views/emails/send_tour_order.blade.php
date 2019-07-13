<div>
    <h5>Xin chào, bạn có 1 đơn đặt tour với thông tin như sau:</h5>
    <p><span style="font-weight:600;">Họ và tên: </span>{{$order_tour['name']}}</p>
    <p><span style="font-weight:600;">Email: </span>{{$order_tour['email']}}</p>
    <p><span style="font-weight:600;">Số điện thoại: </span>{{$order_tour['phone']}}</p>
    <p><span style="font-weight:600;">Địa chỉ: </span>{{$order_tour['address']}}</p>
    <p><span style="font-weight:600;">Ghi chú: </span>{{$order_tour['notes']}}</p>
    <p><span style="font-weight:600;">Đặt {{$order_tour['tickets']}} vé người lớn, {{$order_tour['kid_tickets']}} vé trẻ em và {{$order_tour['baby_tickets']}} vé em bé của tour {{$order_tour['tour']->title}} đi chuyến {{$order_tour['tour_detail']->start_date}}</span></p>
</div>
