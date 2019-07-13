<?php

namespace App\Repositories\API;

use Illuminate\Support\Facades\Cache;
use Response;
use Mail;
use Carbon\Carbon;

use App\Models\Tour;
use App\Models\TourDetail;
use App\Models\TourOrder;

use App\Repositories\API\BaseRepository;
use App\Mail\SendTourOrder;

class TourRepository extends BaseRepository
{
    public function getTour($id)
    {
        $data = Cache::remember('get-tour'.$id, 22 * 60, function () use ($id) {
            $data = [
                "banner" => $this->getBanner('tour-detail', $id),
                "tour" => $this->getTourData($id),
                "datatable" => $this->getTourDetailDatable($id),
                "suggested_tours" => $this->getSuggestTours($id)
            ];
            return $data;
        });
        return $data;
    }

    public function getTourDetailDatable($id)
    {
        $data = [
            "columns" => [
                [
                    "label" => "Ngày khởi hành",
                    "field" => "start_date",
                    "width" => 200
                ],
                [
                    "label" => "Chuyến bay đi",
                    "field" => "flight_out",
                    "width" => 300
                ],
                [
                    "label" => "Chuyến bay về",
                    "field" => "flight_in",
                    "width" => 300
                ],
                [
                    "label" => "Giá",
                    "field" => "price",
                    "width" => 150
                ],
                [
                    "label" => "Giá trẻ em",
                    "field" => "kid_price",
                    "width" => 150
                ],
                [
                    "label" => "Giá em bé",
                    "field" => "baby_price",
                    "width" => 150
                ],
                [
                    "label" => "Giá phòng đơn",
                    "field" => "single_room_price",
                    "width" => 150
                ]
            ],
            "rows" => $this->getListTourDetails($id)
        ];
        return $data;
    }

    public function getListTourDetails($id)
    {
        $tour_details = TourDetail::select([
            'start_date', 'flight_out', 'flight_in', 'price',
            'kid_price', 'baby_price', 'single_room_price'
        ])
        ->where('tour_id', $id)->get();
        foreach ($tour_details as $item) {
            $item->price = $this->parsePrice($item->price);
            $item->kid_price = $this->parsePrice($item->kid_price);
            $item->baby_price = $this->parsePrice($item->baby_price);
            $item->single_room_price = $this->parsePrice($item->single_room_price);
        }

        return $tour_details;
    }

    public function parsePrice($price)
    {
        if ($price <= 0) {
            return 'Theo chính sách tour';
        } else {
            return format_price($price);
        }
    }

    public function getTourData($id)
    {
        $data = null;

        switch ($id) {
            case 1:
            $data = [
                "id" => $id,
                "code" => "STN084-2019-01532",
                "title" => "DU LỊCH NHA TRANG - ĐÀ LẠT (LỄ 30/4)",
                "time_range" => "5 ngày 4 đêm",
                "vehicle" => "Đi về bằng xe",
                "from_place" => "TP. Hồ Chí Minh",
                "to_place" => "Đà Lạt - Nha Trang",
                "start_date" => "27/04/2019",
                "price" => 7779000,
                "kid_price" => 3890000,
                "baby_price" => 0,
                "tour_attractions" => "Tắm biển Dốc Lết - Một trong những bãi biển êm, đẹp, nổi tiếng của tỉnh Khánh Hòa. <br /> Thư giãn và tắm khoáng trung tâm suối khoáng nóng I resort Nha Trang (tự túc chi phí tắm bùn các loại). <br /> Viếng Thiền viện Trúc Lâm - nơi ngắm được toàn cảnh hồ Tuyền Lâm. <br /> Tham quan Khu Du Lịch Trang Trại Rau và Hoa, với bốn bề rau và hoa đẹp tuyệt vời.",
                "images" => [
                    "https://saigontourist.net/uploads/destination/TrongNuoc/Dalat/Domaine-de-Marie-church-Da-Lat.jpg",
                    "https://saigontourist.net/uploads/destination/TrongNuoc/Dalat/Langbiang-dalat-vietnam_519197881.jpg",
                    "https://saigontourist.net/uploads/destination/TrongNuoc/Dalat/Lam-Vien-Square-dalat_412752700.jpg",
                    "https://saigontourist.net/uploads/destination/TrongNuoc/Nhatrang/towers-of-Po-Nagar_406846864.jpg",
                    "https://saigontourist.net/uploads/destination/TrongNuoc/Nhatrang/view-on-Nha-Trang.jpg"
                ],
                "schedules" => [
                    [
                        "id" => 1,
                        "short_title" => "Ngày 1",
                        "title" => "NGÀY 01: TP. HỒ CHÍ MINH - NHA TRANG (Ăn sáng, trưa, chiều)",
                        "content" => "Đón quý khách tại văn phòng Saigontourist (lúc 06h00 sáng tại 01 Nguyễn Chí Thanh, F9, Q5 hoặc 06h30 sáng tại 102 Nguyễn Huệ, Q1), khởi hành đi Nha Trang. Trên đường đi, chiêm ngưỡng vẻ đẹp của biển Cà Ná - một trong những bãi biển đẹp của miền Trung. Đến Cam Ranh, vào Nha Trang theo cung đường Sông Lô Hòn Rớ thơ mộng chạy dọc theo bờ biển. Tham quan Khu tưởng niệm chiến sĩ Gạc Ma, nơi tưởng nhớ 64 chiến sĩ Hải quân Nhân dân Việt Nam đã hi sinh khi bảo vệ chủ quyền Tổ quốc tại đảo Gạc Ma ngày 14/03/1988. Nhận phòng và nghỉ đêm tại Nha Trang.",
                        "images" => [
                            [
                                "id" => 1,
                                "title" => "Biển Cà Ná",
                                "image" => "https://saigontourist.net/uploads/destination/TrongNuoc/Nhatrang/Ca-Na-beach_781076338.jpg"
                            ]
                        ]
                    ],
                    [
                        "id" => 2,
                        "short_title" => "Ngày 2",
                        "title" => "NGÀY 02: NHA TRANG - DỐC LẾT (Ăn sáng, trưa)",
                        "content" => "Buổi sáng, tham quan và tắm biển tại bãi Dốc Lết, tham quan Làng Yến Mai Sinh. Buổi chiều, đoàn tự do nghỉ ngơi thư giãn hoặc xe đưa những quý khách đã đăng ký tham quan đến Vinpearl Land. <br /> Lựa chọn (tự túc chi phí di chuyển và tham quan): Quý khách có thể tham quan Khu vui chơi giải trí Vinpearl Land hệ thống cáp treo vượt biển dài nhất thế giới; Thủy cung lớn và hiện đại nhất Việt Nam; Khu trò chơi trong nhà hoặc chinh phục thử thách cao độ từ hàng chục trò chơi cảm giác mạnh tại Khu trò chơi ngoài trời và Công viên nước ngọt trên bãi biển đầu tiên & duy nhất tại Việt Nam; phòng chiếu phim 4D, chương trình biểu diễn nhạc nước.<br /> Quý khách tự túc ăn tối và phương tiện từ Vinpearl Land về lại khách sạn. Nghỉ đêm tại Nha Trang.",
                        "images" => [
                            [
                                "id" => 1,
                                "title" => "Biển Dốc Lết",
                                "image" => "https://saigontourist.net/uploads/destination/TrongNuoc/Nhatrang/Doc-Let-beach_383171023.jpg"
                            ]
                        ]
                    ],
                    [
                        "id" => 3,
                        "short_title" => "Ngày 3",
                        "title" => "NGÀY 03: NHA TRANG - I RESORT -  ĐÀ LẠT (Ăn sáng, trưa, chiều )",
                        "content" => "Buổi sáng, quý khách tham ,, trung tâm suối khoáng nóng I resort Nha Trang - thư giãn và tắm khoáng (tự túc chi phí tắm bùn các loại). Mua sắm đặc sản tại chợ Đầm. Khởi hành đi Đà Lạt theo cung đường mới tuyệt đẹp dài khoảng 90km, xuyên qua rừng núi bạt ngàn. Xe đưa đoàn đến Quảng trường Lâm Viên với không gian rộng lớn, thoáng mát hướng ra hồ Xuân Hương cùng công trình nghệ thuật khối bông hoa dã quỳ và khối nụ hoa Atiso khổng lồ được thiết kế bằng kính màu rất đẹp mắt. Nghỉ đêm tại Đà Lạt.",
                        "images" => [
                            [
                                "id" => 1,
                                "title" => "Quảng trường Lâm Viên",
                                "image" => "https://saigontourist.net/uploads/destination/TrongNuoc/Dalat/Lam-Vien-Square-dalat_412752700.jpg"
                            ]
                        ]
                    ],
                    [
                        "id" => 4,
                        "short_title" => "Ngày 4",
                        "title" => "NGÀY 04: ĐÀ LẠT - LANGBIANG (Ăn sáng, trưa, chiều)",
                        "content" => "Buổi sáng, tham quan nhà thờ Domain De Marie, theo cung đường Mang – Ling, đi ngang qua khu vườn hồng Cam Ly, quý khách đến với khu dã ngoại núi Langbian tham quan đồi Mimosa, thung lũng Trăm Năm, chinh phục núi Langbian (tự túc phí xe Jeep). Buổi chiều, tham quan Thiền viện Trúc Lâm - nơi ngắm được toàn cảnh hồ Tuyền Lâm, thác Datanla (tự túc chi phí tham gia trò chơi máng trượt). Mua sắm tại chợ Đà Lạt. Nghỉ đêm tại Đà Lạt.",
                        "images" => [
                            [
                                "id" => 1,
                                "title" => "Nhà thờ Domain De Marie",
                                "image" => "https://saigontourist.net/uploads/destination/TrongNuoc/Dalat/Domain-de-Marie-church.jpg"
                            ]
                        ]
                    ],
                    [
                        "id" => 5,
                        "short_title" => "Ngày 5",
                        "title" => "NGÀY 05: ĐÀ LẠT - TÀ NUNG - TP. HỒ CHÍ MINH (Ăn sáng, trưa)",
                        "content" => "Sau bữa sáng, quý khách trả phòng, khởi hành về TP.HCM. Quý khách tham quan Khu Du Lịch Trang Trại Rau và Hoa, nằm trải rộng cả một thung lũng với bốn bề là rau và hoa đẹp tuyệt vời. Dừng chân thưởng thức trà và cà phê (tư túc chi phí) tại café Mê Linh - quán cà phê chồn sở hữu cảnh quan thiên nhiên hùng vĩ hiếm có tại Đà Lạt. Ngắm nhìn không gian bạt ngàn rẫy cà phê. Tham quan chuồng trại nuôi chồn, xưởng sản xuất và tìm hiểu về cách thức để làm ra cà phê chồn đúng chất. Về tới TP.HCM, đưa quý khách về văn phòng Saigontourist. Kết thúc chương trình.",
                        "images" => [
                            [
                                "id" => 1,
                                "title" => "Khu Du Lịch Trang Trại Rau và Hoa",
                                "image" => "https://saigontourist.net/uploads/destination/TrongNuoc/Dalat/Vegetable-fields-and-Housein-highland_634336823.jpg"
                            ]
                        ]
                    ]
                ],
                "tour_policies" => "<div class=\"commonInfo\"> <p> </p><p><em><strong>* Giá tour bao gồm:&nbsp;&nbsp;</strong></em><br> -&nbsp;Chi phí xe máy lạnh phục vụ theo chương trình.<br> -&nbsp;Chi phí ăn – uống theo chương trình.<br> -&nbsp;Chi phí khách sạn tiêu chuẩn 2 khách/phòng. Lẻ khách ngủ&nbsp;giường phụ&nbsp;hoặc chịu chi phí phụ thu phòng đơn: <strong>+ 3.100.000 đ/ khách.</strong><br> + Nha Trang: <strong>Happy Light</strong>,..<br> + Đà Lạt : <strong>Da Lat Plaza</strong>,..<br> <em>Hoặc các khách sạn khách tương đương&nbsp;</em><br> -&nbsp;Chi phí tham quan, hướng dẫn viên tiếng Việt<br> <em><strong>Quà tặng: Nón, nước suối, khăn lạnh, viết…</strong></em></p> <p><em><strong>* Giá tour không bao gồm:&nbsp;&nbsp;</strong></em><br> -&nbsp;Ăn uống ngoài chương trình, giặt ủi, điện thoại và các chi phí cá nhân.<br> -&nbsp;Vé Vinpearlland.<br> - Phụ thu người nước ngoài : <strong>+ 250.000 đ/ khách/ tour. </strong><br> - Phụ thu&nbsp;phòng đơn: <strong>+ 3.100.000 đ/ khách/ tour.</strong></p> <p><strong>THÔNG TIN HƯỚNG DẪN</strong></p> <p><em><strong>* Vé trẻ em:&nbsp;&nbsp;</strong></em><br> -&nbsp;Vé tour: Trẻ em từ&nbsp; 6 đến 11 tuổi mua một nửa giá vé người lớn, trẻ em trên 11 tuổi mua vé như người lớn.<br> -&nbsp;Đối với trẻ em dưới 6 tuổi, gia đình tự lo cho bé ăn ngủ và tự trả phí tham quan (nếu có). Hai người lớn chỉ được kèm một trẻ em. Từ trẻ thứ 2 trở lên, mỗi em phải 50% giá vé người lớn.<br> -&nbsp;Tiêu chuẩn 50% giá tour bao gồm: Suất ăn, ghế ngồi và ngủ ghép chung với gia đình.</p> <p><em><strong>* Hành lý và giấy tờ tùy thân:&nbsp;</strong></em>&nbsp;<br> -&nbsp;Du khách mang theo giấy CMND hoặc Hộ chiếu. Đối với du khách là Việt kiều, Quốc tế nhập cảnh Việt Nam bằng visa rời, vui lòng mang theo visa khi đăng ký và đi tour.<br> -&nbsp;Khách lớn tuổi (từ 70 tuổi trở lên), khách tàn tật tham gia tour, phải có thân nhân đi kèm và cam kết đảm bảo đủ sức khỏe khi tham gia tour du lịch.<br> -&nbsp;Trẻ em dưới 14 tuổi khi đi tour phải mang theo Giấy khai sinh hoặc Hộ chiếu. Trẻ em từ 14 tuổi trở lên phải mang theo giấy CMND hoặc Hộ chiếu riêng<br> -&nbsp;Tất cả giấy tờ tùy thân mang theo đều phải bản chính<br> -&nbsp;Du khách mang theo hành lý gọn nhẹ và phải tự bảo quản hành lý, tiền bạc, tư trang trong suốt thời gian đi du lịch.<br> -&nbsp;Khách Việt Nam ở cùng phòng với khách Quốc tế&nbsp; hoặc Việt kiều yêu cầu phải có giấy hôn thú.</p> <p><em><strong>* Trường hợp hủy vé tour, du khách vui lòng thanh toán các khoản lệ phí hủy tour như sau:</strong></em><br> a) Đối với dịp Lễ, Tết:<br> -&nbsp;Du khách chuyển đổi tour sang ngày khác và báo trước ngày khởi hành trước 15 ngày sẽ không chịu phí (không áp dụng các tour ở KS 4- 5 sao), nếu trễ hơn sẽ căn cứ theo qui định hủy phạt&nbsp; phía dưới và chỉ được chuyển ngày khởi hành tour 1 (một) lần.<br> -&nbsp;Hủy vé trong vòng 24 giờ hoặc ngay ngày khởi hành, chịu phạt 100% tiền tour.<br> -&nbsp;Hủy vé trước ngày khởi hành từ 2 - 7 ngày, chịu phạt 80% tiền tour.<br> -&nbsp;Hủy vé trước ngày khởi hành từ 8 - 15 ngày, chịu phạt 50% tiền tour.<br> -&nbsp;Hủy vé trước ngày khởi hành 15 ngày, chịu phạt 20% tiền tour</p> <p>b) Sau khi hủy tour, du khách vui lòng đến nhận tiền trong vòng 15 ngày kể từ ngày kết thúc tour. Chúng tôi chỉ&nbsp; thanh toán trong khỏang thời gian nói trên.</p> <p>c) Trường hợp hủy tour do sự cố khách quan như thiên tai, dịch bệnh hoặc do tàu thủy, xe lửa, máy bay hoãn/hủy chuyến, Saigontourist sẽ không chịu trách nhiệm bồi thường thêm bất kỳ chi phí nào khác ngoài việc hoàn trả chi phí những dịch vụ chưa được sử dụng của tour đó</p> <p><em><strong>* Ghi chú khác:</strong></em><br> -&nbsp;Công ty xuất hóa đơn cho du khách có nhu cầu (Trong thời hạn 7 ngày sau khi kết thúc chương trình du lịch). Du khách được chọn một trong những chương trình khuyến mãi dành cho khách lẻ định kỳ (Nếu có).<br> -<strong>&nbsp;Du khách có mặt tại điểm đón trước 15 phút. Du khách đến trễ khi xe đã khởi hành hoặc hủy tour không báo trước vui lòng chịu phí như ‘hủy vé ngay ngày khởi hành’.</strong></p> <p></p> </div>"
            ];
            break;
            default:
            $data = [
                "id" => 1,
                "code" => "STN084-2019-01532",
                "title" => "DU LỊCH NHA TRANG - ĐÀ LẠT (LỄ 30/4)",
                "time_range" => "5 ngày 4 đêm",
                "vehicle" => "Đi về bằng xe",
                "from_place" => "TP. Hồ Chí Minh",
                "to_place" => "Đà Lạt - Nha Trang",
                "start_date" => "27/04/2019",
                "price" => 7779000,
                "kid_price" => 3890000,
                "baby_price" => 0,
                "tour_attractions" => [
                    "Tắm biển Dốc Lết - Một trong những bãi biển êm, đẹp, nổi tiếng của tỉnh Khánh Hòa.",
                    "Thư giãn và tắm khoáng trung tâm suối khoáng nóng I resort Nha Trang (tự túc chi phí tắm bùn các loại).",
                    "Viếng Thiền viện Trúc Lâm - nơi ngắm được toàn cảnh hồ Tuyền Lâm.",
                    "Tham quan Khu Du Lịch Trang Trại Rau và Hoa, với bốn bề rau và hoa đẹp tuyệt vời."
                ],
                "images" => [
                    "https://saigontourist.net/uploads/destination/TrongNuoc/Dalat/Domaine-de-Marie-church-Da-Lat.jpg",
                    "https://saigontourist.net/uploads/destination/TrongNuoc/Dalat/Langbiang-dalat-vietnam_519197881.jpg",
                    "https://saigontourist.net/uploads/destination/TrongNuoc/Dalat/Lam-Vien-Square-dalat_412752700.jpg",
                    "https://saigontourist.net/uploads/destination/TrongNuoc/Nhatrang/towers-of-Po-Nagar_406846864.jpg",
                    "https://saigontourist.net/uploads/destination/TrongNuoc/Nhatrang/view-on-Nha-Trang.jpg"
                ],
                "schedules" => [
                    [
                        "id" => 1,
                        "short_title" => "Ngày 1",
                        "title" => "NGÀY 01: TP. HỒ CHÍ MINH - NHA TRANG (Ăn sáng, trưa, chiều)",
                        "content" => "Đón quý khách tại văn phòng Saigontourist (lúc 06h00 sáng tại 01 Nguyễn Chí Thanh, F9, Q5 hoặc 06h30 sáng tại 102 Nguyễn Huệ, Q1), khởi hành đi Nha Trang. Trên đường đi, chiêm ngưỡng vẻ đẹp của biển Cà Ná - một trong những bãi biển đẹp của miền Trung. Đến Cam Ranh, vào Nha Trang theo cung đường Sông Lô Hòn Rớ thơ mộng chạy dọc theo bờ biển. Tham quan Khu tưởng niệm chiến sĩ Gạc Ma, nơi tưởng nhớ 64 chiến sĩ Hải quân Nhân dân Việt Nam đã hi sinh khi bảo vệ chủ quyền Tổ quốc tại đảo Gạc Ma ngày 14/03/1988. Nhận phòng và nghỉ đêm tại Nha Trang.",
                        "images" => [
                            [
                                "id" => 1,
                                "title" => "Biển Cà Ná",
                                "image" => "https://saigontourist.net/uploads/destination/TrongNuoc/Nhatrang/Ca-Na-beach_781076338.jpg"
                            ]
                        ]
                    ],
                    [
                        "id" => 2,
                        "short_title" => "Ngày 2",
                        "title" => "NGÀY 02: NHA TRANG - DỐC LẾT (Ăn sáng, trưa)",
                        "content" => "Buổi sáng, tham quan và tắm biển tại bãi Dốc Lết, tham quan Làng Yến Mai Sinh. Buổi chiều, đoàn tự do nghỉ ngơi thư giãn hoặc xe đưa những quý khách đã đăng ký tham quan đến Vinpearl Land. <br /> Lựa chọn (tự túc chi phí di chuyển và tham quan): Quý khách có thể tham quan Khu vui chơi giải trí Vinpearl Land hệ thống cáp treo vượt biển dài nhất thế giới; Thủy cung lớn và hiện đại nhất Việt Nam; Khu trò chơi trong nhà hoặc chinh phục thử thách cao độ từ hàng chục trò chơi cảm giác mạnh tại Khu trò chơi ngoài trời và Công viên nước ngọt trên bãi biển đầu tiên & duy nhất tại Việt Nam; phòng chiếu phim 4D, chương trình biểu diễn nhạc nước.<br /> Quý khách tự túc ăn tối và phương tiện từ Vinpearl Land về lại khách sạn. Nghỉ đêm tại Nha Trang.",
                        "images" => [
                            [
                                "id" => 1,
                                "title" => "Biển Dốc Lết",
                                "image" => "https://saigontourist.net/uploads/destination/TrongNuoc/Nhatrang/Doc-Let-beach_383171023.jpg"
                            ]
                        ]
                    ],
                    [
                        "id" => 3,
                        "short_title" => "Ngày 3",
                        "title" => "NGÀY 03: NHA TRANG - I RESORT -  ĐÀ LẠT (Ăn sáng, trưa, chiều )",
                        "content" => "Buổi sáng, quý khách tham ,, trung tâm suối khoáng nóng I resort Nha Trang - thư giãn và tắm khoáng (tự túc chi phí tắm bùn các loại). Mua sắm đặc sản tại chợ Đầm. Khởi hành đi Đà Lạt theo cung đường mới tuyệt đẹp dài khoảng 90km, xuyên qua rừng núi bạt ngàn. Xe đưa đoàn đến Quảng trường Lâm Viên với không gian rộng lớn, thoáng mát hướng ra hồ Xuân Hương cùng công trình nghệ thuật khối bông hoa dã quỳ và khối nụ hoa Atiso khổng lồ được thiết kế bằng kính màu rất đẹp mắt. Nghỉ đêm tại Đà Lạt.",
                        "images" => [
                            [
                                "id" => 1,
                                "title" => "Quảng trường Lâm Viên",
                                "image" => "https://saigontourist.net/uploads/destination/TrongNuoc/Dalat/Lam-Vien-Square-dalat_412752700.jpg"
                            ]
                        ]
                    ],
                    [
                        "id" => 4,
                        "short_title" => "Ngày 4",
                        "title" => "NGÀY 04: ĐÀ LẠT - LANGBIANG (Ăn sáng, trưa, chiều)",
                        "content" => "Buổi sáng, tham quan nhà thờ Domain De Marie, theo cung đường Mang – Ling, đi ngang qua khu vườn hồng Cam Ly, quý khách đến với khu dã ngoại núi Langbian tham quan đồi Mimosa, thung lũng Trăm Năm, chinh phục núi Langbian (tự túc phí xe Jeep). Buổi chiều, tham quan Thiền viện Trúc Lâm - nơi ngắm được toàn cảnh hồ Tuyền Lâm, thác Datanla (tự túc chi phí tham gia trò chơi máng trượt). Mua sắm tại chợ Đà Lạt. Nghỉ đêm tại Đà Lạt.",
                        "images" => [
                            [
                                "id" => 1,
                                "title" => "Nhà thờ Domain De Marie",
                                "image" => "https://saigontourist.net/uploads/destination/TrongNuoc/Dalat/Domain-de-Marie-church.jpg"
                            ]
                        ]
                    ],
                    [
                        "id" => 5,
                        "short_title" => "Ngày 5",
                        "title" => "NGÀY 05: ĐÀ LẠT - TÀ NUNG - TP. HỒ CHÍ MINH (Ăn sáng, trưa)",
                        "content" => "Sau bữa sáng, quý khách trả phòng, khởi hành về TP.HCM. Quý khách tham quan Khu Du Lịch Trang Trại Rau và Hoa, nằm trải rộng cả một thung lũng với bốn bề là rau và hoa đẹp tuyệt vời. Dừng chân thưởng thức trà và cà phê (tư túc chi phí) tại café Mê Linh - quán cà phê chồn sở hữu cảnh quan thiên nhiên hùng vĩ hiếm có tại Đà Lạt. Ngắm nhìn không gian bạt ngàn rẫy cà phê. Tham quan chuồng trại nuôi chồn, xưởng sản xuất và tìm hiểu về cách thức để làm ra cà phê chồn đúng chất. Về tới TP.HCM, đưa quý khách về văn phòng Saigontourist. Kết thúc chương trình.",
                        "images" => [
                            [
                                "id" => 1,
                                "title" => "Khu Du Lịch Trang Trại Rau và Hoa",
                                "image" => "https://saigontourist.net/uploads/destination/TrongNuoc/Dalat/Vegetable-fields-and-Housein-highland_634336823.jpg"
                            ]
                        ]
                    ]
                ],
                "policies" => "<div class=\"commonInfo\"> <p> </p><p><em><strong>* Giá tour bao gồm:&nbsp;&nbsp;</strong></em><br> -&nbsp;Chi phí xe máy lạnh phục vụ theo chương trình.<br> -&nbsp;Chi phí ăn – uống theo chương trình.<br> -&nbsp;Chi phí khách sạn tiêu chuẩn 2 khách/phòng. Lẻ khách ngủ&nbsp;giường phụ&nbsp;hoặc chịu chi phí phụ thu phòng đơn: <strong>+ 3.100.000 đ/ khách.</strong><br> + Nha Trang: <strong>Happy Light</strong>,..<br> + Đà Lạt : <strong>Da Lat Plaza</strong>,..<br> <em>Hoặc các khách sạn khách tương đương&nbsp;</em><br> -&nbsp;Chi phí tham quan, hướng dẫn viên tiếng Việt<br> <em><strong>Quà tặng: Nón, nước suối, khăn lạnh, viết…</strong></em></p> <p><em><strong>* Giá tour không bao gồm:&nbsp;&nbsp;</strong></em><br> -&nbsp;Ăn uống ngoài chương trình, giặt ủi, điện thoại và các chi phí cá nhân.<br> -&nbsp;Vé Vinpearlland.<br> - Phụ thu người nước ngoài : <strong>+ 250.000 đ/ khách/ tour. </strong><br> - Phụ thu&nbsp;phòng đơn: <strong>+ 3.100.000 đ/ khách/ tour.</strong></p> <p><strong>THÔNG TIN HƯỚNG DẪN</strong></p> <p><em><strong>* Vé trẻ em:&nbsp;&nbsp;</strong></em><br> -&nbsp;Vé tour: Trẻ em từ&nbsp; 6 đến 11 tuổi mua một nửa giá vé người lớn, trẻ em trên 11 tuổi mua vé như người lớn.<br> -&nbsp;Đối với trẻ em dưới 6 tuổi, gia đình tự lo cho bé ăn ngủ và tự trả phí tham quan (nếu có). Hai người lớn chỉ được kèm một trẻ em. Từ trẻ thứ 2 trở lên, mỗi em phải 50% giá vé người lớn.<br> -&nbsp;Tiêu chuẩn 50% giá tour bao gồm: Suất ăn, ghế ngồi và ngủ ghép chung với gia đình.</p> <p><em><strong>* Hành lý và giấy tờ tùy thân:&nbsp;</strong></em>&nbsp;<br> -&nbsp;Du khách mang theo giấy CMND hoặc Hộ chiếu. Đối với du khách là Việt kiều, Quốc tế nhập cảnh Việt Nam bằng visa rời, vui lòng mang theo visa khi đăng ký và đi tour.<br> -&nbsp;Khách lớn tuổi (từ 70 tuổi trở lên), khách tàn tật tham gia tour, phải có thân nhân đi kèm và cam kết đảm bảo đủ sức khỏe khi tham gia tour du lịch.<br> -&nbsp;Trẻ em dưới 14 tuổi khi đi tour phải mang theo Giấy khai sinh hoặc Hộ chiếu. Trẻ em từ 14 tuổi trở lên phải mang theo giấy CMND hoặc Hộ chiếu riêng<br> -&nbsp;Tất cả giấy tờ tùy thân mang theo đều phải bản chính<br> -&nbsp;Du khách mang theo hành lý gọn nhẹ và phải tự bảo quản hành lý, tiền bạc, tư trang trong suốt thời gian đi du lịch.<br> -&nbsp;Khách Việt Nam ở cùng phòng với khách Quốc tế&nbsp; hoặc Việt kiều yêu cầu phải có giấy hôn thú.</p> <p><em><strong>* Trường hợp hủy vé tour, du khách vui lòng thanh toán các khoản lệ phí hủy tour như sau:</strong></em><br> a) Đối với dịp Lễ, Tết:<br> -&nbsp;Du khách chuyển đổi tour sang ngày khác và báo trước ngày khởi hành trước 15 ngày sẽ không chịu phí (không áp dụng các tour ở KS 4- 5 sao), nếu trễ hơn sẽ căn cứ theo qui định hủy phạt&nbsp; phía dưới và chỉ được chuyển ngày khởi hành tour 1 (một) lần.<br> -&nbsp;Hủy vé trong vòng 24 giờ hoặc ngay ngày khởi hành, chịu phạt 100% tiền tour.<br> -&nbsp;Hủy vé trước ngày khởi hành từ 2 - 7 ngày, chịu phạt 80% tiền tour.<br> -&nbsp;Hủy vé trước ngày khởi hành từ 8 - 15 ngày, chịu phạt 50% tiền tour.<br> -&nbsp;Hủy vé trước ngày khởi hành 15 ngày, chịu phạt 20% tiền tour</p> <p>b) Sau khi hủy tour, du khách vui lòng đến nhận tiền trong vòng 15 ngày kể từ ngày kết thúc tour. Chúng tôi chỉ&nbsp; thanh toán trong khỏang thời gian nói trên.</p> <p>c) Trường hợp hủy tour do sự cố khách quan như thiên tai, dịch bệnh hoặc do tàu thủy, xe lửa, máy bay hoãn/hủy chuyến, Saigontourist sẽ không chịu trách nhiệm bồi thường thêm bất kỳ chi phí nào khác ngoài việc hoàn trả chi phí những dịch vụ chưa được sử dụng của tour đó</p> <p><em><strong>* Ghi chú khác:</strong></em><br> -&nbsp;Công ty xuất hóa đơn cho du khách có nhu cầu (Trong thời hạn 7 ngày sau khi kết thúc chương trình du lịch). Du khách được chọn một trong những chương trình khuyến mãi dành cho khách lẻ định kỳ (Nếu có).<br> -<strong>&nbsp;Du khách có mặt tại điểm đón trước 15 phút. Du khách đến trễ khi xe đã khởi hành hoặc hủy tour không báo trước vui lòng chịu phí như ‘hủy vé ngay ngày khởi hành’.</strong></p> <p></p> </div>"
            ];
            break;
        }

        $tour = Tour::find($id);
        $tour->from_place = $tour->fromPlace();
        $tour->to_place = $tour->toPlace();
        $tour->images = $tour->images();
        if (isset($tour->schedules)) {
            foreach ($tour->schedules as $item) {
                if (isset($item->images)) {
                    foreach ($item->images as $i) {
                        $i->image = asset('storage/' . $i->origin);
                    }
                }
            }
        }
        $now = Carbon::now()->format('m/d/Y');
        $tour->details = TourDetail::where('tour_id', $id)
        ->where('start_date', '>=', $now)
        ->get();
        $tour->tour_detail_options = $this->generateTourDetailOptions($id);

        return $tour;
    }

    public function generateTourDetailOptions($id)
    {
        $now = Carbon::now()->format('m/d/Y');
        $options = TourDetail::select(['id as value', 'start_date as label'])
        ->where('tour_id', $id)
        ->where('start_date', '>=', $now)
        ->get();

        return $options;
    }

    public function sendTourOrder($request)
    {
        $input = $request->all();
        $model = new TourOrder;

        $model->tour_id = $input['tour_id'];
        $model->tour_detail_id = $input['tour_detail_id'];
        $model->tickets = $input['tickets'];
        $model->kid_tickets = $input['kid_tickets'];
        $model->baby_tickets = $input['baby_tickets'];
        $model->name = $input['name'];
        $model->phone = $input['phone'];
        $model->email = $input['email'];
        $model->address = $input['address'];
        $model->notes = $input['notes'];
        $model->status = WAITING;

        $model->save();

        $order_tour = [
            "name" => $input['name'],
            "phone" => $input['phone'],
            "email" => $input['email'],
            "address" => $input['address'],
            "notes" => $input['notes'],
            "tickets" => $input['tickets'],
            "kid_tickets" => $input['kid_tickets'],
            "baby_tickets" => $input['baby_tickets'],
            "tour" => $model->tour,
            "tour_detail" => $model->tour_detail
        ];

        Mail::to('info@tavivu.net')->send(new SendTourOrder($order_tour));

        return 'Email was sent';
    }

    public function getSuggestTours($id)
    {
        $tours = Tour::where('id', '!=', $id)->where('is_coming', ACTIVE)->orderBy('index', 'asc')->limit(6)->get();

        foreach ($tours as $item) {
            $item->generateData();
        }

        $data = [
            "title" => [
                "highlight" => "Tours",
                "normal" => "được đề xuất giành cho bạn"
            ],
            "data" => $tours
        ];
        return $data;
    }
}
