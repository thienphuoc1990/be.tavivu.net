<?php

namespace App\Repositories\API;

use Illuminate\Support\Facades\Cache;
use Response;
use App\Models\Tour;

class BaseRepository
{
    public function getBanner($page = 'home', $id = null)
    {
        $banner = null;
        switch ($page) {
            case 'home':
                $banner = [
                    "is_slider" => true,
                    "slides" => [
                        [
                            "image" => asset('storage/' . 'banners/japan.jpg'),
                            "title" => "Khám phá xứ sở Phù Tang"
                        ],
                        [
                            "image" => asset('storage/' . 'banners/marina-bay-of-singapore.jpg'),
                            "title" => "SINGAPORE - MALAYSIA"
                        ],
                        [
                            "image" => asset('storage/' . 'banners/singapore.jpg'),
                            "title" => "SINGAPORE - MALAYSIA"
                        ]
                    ]
                ];
                break;

            case 'tour-detail':
                $banner = $this->getTourBanners($id);
                break;

            default:
                $banner = [
                    "is_slider" => true,
                    "slides" => [
                        [
                            "image" => asset('storage/' . 'banners/japan.jpg')
                        ],
                        [
                            "image" => asset('storage/' . 'banners/marina-bay-of-singapore.jpg')
                        ],
                        [
                            "image" => asset('storage/' . 'banners/singapore.jpg')
                        ]
                    ],
                    "image" => asset('storage/' . 'banners/marina-bay-of-singapore.jpg')
                ];
                break;
        }

        return $banner;
    }

    public function getTourBanners($id) {
        $tour = Tour::find($id);

        $banner_arr = [];
        foreach($tour->banners_rel as $b) {
            $tmp = [
                "image" => asset('storage/' . $b->origin),
                "title" => $tour->title,
                "sub_title" => $tour->sub_title
            ];

            array_push($banner_arr, $tmp);
        }

        $banner = [
            "is_slider" => true,
            "slides" => $banner_arr
        ];

        return $banner;
    }

    public function getConfigs()
    {
        $data = [
            "proof" => [
                "image" => asset('storage/' . 'others/20150827110756-dathongbao.png'),
                "linkto" => "http://online.gov.vn/HomePage/CustomWebsiteDisplay.aspx?DocId=56128"
            ],
            "contact_info" => [
                "company_name" => "CÔNG TY TNHH DỊCH VỤ LỮ HÀNH TAVIVU",
                "address" => "25 Trần Hưng Đạo Phường 6, Quận 5, TP. Hồ Chí Minh",
                "phone_number" => "02866 565 368",
                "phone" => "02866 565 368",
                "fax" => "(+84 76) 4832 673",
                "email" => "info@tavivu.net",
                "hotline" => "0338 565 368"
            ],
            "inside_tours" => [
                "title" => "Tour trong nước",
                "links" => [
                    [
                        "title" => "Nha Trang",
                        "linkto" => "/tours-trong-nuoc?fp=1"
                    ],
                    [
                        "title" => "TP. Hồ Chí Minh",
                        "linkto" => "/tours-trong-nuoc?fp=2"
                    ]
                    // ,
                    // [
                    //     "title" => "Cần Thơ",
                    //     "linkto" => "tours"
                    // ],
                    // [
                    //     "title" => "Sapa",
                    //     "linkto" => "tours"
                    // ],
                    // [
                    //     "title" => "Hội An",
                    //     "linkto" => "tours"
                    // ],
                    // [
                    //     "title" => "Phú Quốc",
                    //     "linkto" => "tours"
                    // ],
                    // [
                    //     "title" => "Đà Lạt",
                    //     "linkto" => "tours"
                    // ],
                    // [
                    //     "title" => "Đà Nẵng",
                    //     "linkto" => "tours-in-country"
                    // ]
                ]
            ],
            "outside_tours" => [
                "title" => "Tour quốc tế",
                "links" => [
                    [
                        "title" => "Trung Quốc",
                        "linkto" => "/tours-quoc-te?tp=23"
                    ],
                    [
                        "title" => "Hồng Kông",
                        "linkto" => "/tours-quoc-te?tp=3"
                    ],
                    [
                        "title" => "Singapore",
                        "linkto" => "/tours-quoc-te?tp=7"
                    ],
                    [
                        "title" => "Nhật Bản",
                        "linkto" => "/tours-quoc-te?tp=24"
                    ]
                    // ,
                    // [
                    //     "title" => "Hàn Quốc",
                    //     "linkto" => "tours"
                    // ],
                    // [
                    //     "title" => "Hawaii",
                    //     "linkto" => "tours"
                    // ],
                    // [
                    //     "title" => "Campuchia",
                    //     "linkto" => "tours"
                    // ],
                    // [
                    //     "title" => "Thái Lan",
                    //     "linkto" => "tours"
                    // ]
                ]
            ],
            "type_tours" => [
                "title" => "Dòng tour",
                "links" => [
                    // [
                    //     "title" => "Cao cấp",
                    //     "linkto" => "tours"
                    // ],
                    [
                        "title" => "Tiêu chuẩn",
                        "linkto" => "/tours-quoc-te"
                    ]
                    // ,
                    // [
                    //     "title" => "Tiết kiệm",
                    //     "linkto" => "tours"
                    // ]
                    ,
                    [
                        "title" => "Giá tốt",
                        "linkto" => "/tours-quoc-te"
                    ]
                ]
            ],
            "infos" => [
                "title" => "Thông tin",
                "links" => [
                    // [
                    //     "title" => "Tin tức",
                    //     "linkto" => "news"
                    // ],
                    // [
                    //     "title" => "Tạp chí du lịch",
                    //     "linkto" => "news"
                    // ],
                    // [
                    //     "title" => "Cẩm nang du lịch",
                    //     "linkto" => "news"
                    // ],
                    // [
                    //     "title" => "Kinh nghiệm",
                    //     "linkto" => "news"
                    // ],
                    [
                        "title" => "Liên hệ",
                        "linkto" => "/lien-he"
                    ],
                    [
                        "title" => "Quy định và hình thức thanh toán",
                        "linkto" => "/thong-tin/quy-dinh-va-hinh-thuc-thanh-toan"
                    ],
                    [
                        "title" => "Chính sách vận chuyển/giao nhận/cài đặt",
                        "linkto" => "/thong-tin/chinh-sach-van-chuyengiao-nhancai-dat"
                    ],
                    [
                        "title" => "Chính sách xác nhận/huỷ tour du lịch",
                        "linkto" => "/thong-tin/chinh-sach-xac-nhanhuy-tour-du-lich"
                    ],
                    [
                        "title" => "Chính sách bảo vệ thông tin cá nhân của người tiêu dùng",
                        "linkto" => "/thong-tin/chinh-sach-bao-ve-thong-tin-ca-nhan-cua-nguoi-tieu-dung"
                    ]
                    // ,
                    // [
                    //     "title" => "FAQ",
                    //     "linkto" => "faq"
                    // ]
                ]
            ]
        ];
        return $data;
    }
}
