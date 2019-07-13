<?php

namespace App\Repositories\API;

use Illuminate\Support\Facades\Cache;
use Response;

use App\Models\Tour;
use App\Models\Place;
use App\Models\Page;
use App\Models\Image;

use App\Repositories\API\BaseRepository;

class PageRepository extends BaseRepository
{
    public function getHome()
    {
        $data = Cache::remember('get-home', 22 * 60, function () {
            $data = [
                "banner" => $this->getBanner(),
                "incoming_tours" => $this->getIncomingTours(),
                "popular_international_places" => $this->getPopularPlaces()
            ];
            return $data;
        });

        return $data;
    }

    public function getAbout()
    {
        $data = [
            "banner" => $this->getBanner('about'),
            "content" => $this->getPageContent('gioi-thieu')
        ];
        return $data;
    }

    public function getPage($page_slug)
    {
        $page  = $this->getPageData($page_slug);

        $data = [
            "banner" => $this->getBanner('about'),
            "content" => $page->content,
            "title" => $page->title
        ];
        return $data;
    }

    public function getToursInCountry($request)
    {
        $data = [
            "banner" => $this->getBanner('tours-in-country'),
            "start_place_options" => $this->getPlaceOptions(IN_COUNTRY),
            "end_place_options" => $this->getPlaceOptions(IN_COUNTRY),
            "tours" => $this->getTours($request, IN_COUNTRY)
        ];
        return $data;
    }

    public function getToursInternational($request)
    {
        $data = [
            "banner" => $this->getBanner('tours-international'),
            "start_place_options" => $this->getPlaceOptions(IN_COUNTRY),
            "end_place_options" => $this->getPlaceOptions(INTERNATIONAL),
            "tours" => $this->getTours($request, INTERNATIONAL)
        ];
        return $data;
    }

    public function getContact()
    {
        $data = [
            "banner" => $this->getBanner('contact'),
            "company" => $this->getCompany()
        ];
        return $data;
    }

    public function getCompany()
    {
        $data = [
            "name" => "CÔNG TY TNHH DỊCH VỤ LỮ HÀNH TAVIVU",
            "latitude" => 10.7527042,
            "longtitude" => 106.6694123,
            "contacts" => [
                [
                    "icon_class" => "fas fa-map-marker-alt",
                    "title" => "Địa chỉ",
                    "content" => "25 Trần Hưng Đạo Phường 6, Quận 5, TP. Hồ Chí Minh"
                ],
                [
                    "icon_class" => "fas fa-phone",
                    "title" => "Tư vấn & đặt dịch vụ",
                    "content" => "<span>Điện thoại:</span> 02866 565 368"
                ],
                [
                    "icon_class" => "fas fa-envelope",
                    "title" => "Email",
                    "content" => "info@tavivu.net"
                ]
            ]
        ];

        return $data;
    }

    public function getPlaceOptions($where = IN_COUNTRY)
    {
        $places = Place::select(['id as value', 'title as label'])
            ->where('type', $where)
            ->get();

        return $places;
    }

    public function getTours($request, $type = null)
    {
        if (
            trim($request->get('sd')) == "" && trim($request->get('s')) == "" && trim($request->get('tp')) == ""
            && trim($request->get('fp')) == "" && trim($request->get('sd')) == ""
        ) {
            $tours = Cache::remember('get-tours'.$type, 22 * 60, function () use ($type) {
                $tours = Tour::select('tours.*');

                if (!is_null($type)) {
                    $tours->where('type', $type);
                }

                $tours = $tours->orderBy('index', 'asc')->groupBy('tours.id')->limit(12)->get();

                foreach ($tours as $item) {
                    $item->generateData();
                }

                return $tours;
            });
        } else {
            // $request->get('keyword')
            $tours = Tour::select('tours.*');

            // start date join table
            if (trim($request->get('sd')) !== "") {
                $tours->join('tour_details', 'tours.id', '=', 'tour_details.tour_id');
            }
            if (!is_null($type)) {
                $tours->where('type', $type);
            }

            // search string
            if (trim($request->get('s')) !== "") {
                $tours->where('title', 'LIKE', '%' . trim($request->get('s')) . '%');
            }

            // to place
            if (trim($request->get('tp')) !== "") {
                $tps = explode(',', $request->get('tp'));
                $tours->where(function ($query) use ($tps) {
                    foreach ($tps as $key => $item) {
                        if ($key == 0) {
                            $query->where('to_place', 'LIKE', '%-' . trim($item) . '-%');
                        } else {
                            $query->orWhere('to_place', 'LIKE', '%-' . trim($item) . '-%');
                        }
                    }
                });
            }

            // from place
            if (trim($request->get('fp')) !== "") {
                $fps = explode(',', $request->get('fp'));
                $tours->where(function ($query) use ($fps) {
                    foreach ($fps as $key => $item) {
                        if ($key == 0) {
                            $query->where('from_place', 'LIKE', '%-' . trim($item) . '-%');
                        } else {
                            $query->orWhere('from_place', 'LIKE', '%-' . trim($item) . '-%');
                        }
                    }
                });
            }

            // start date
            if (trim($request->get('sd')) !== "") {
                $tours->where('start_date', '>=', trim($request->get('sd')));
            }

            $tours = $tours->orderBy('index', 'asc')->groupBy('tours.id')->limit(12)->get();

            foreach ($tours as $item) {
                $item->generateData();
            }
        }

        return $tours;
    }

    public function getIncomingTours()
    {
        $tours = Tour::where('is_coming', ACTIVE)->orderBy('index', 'asc')->limit(6)->get();
        foreach ($tours as $item) {
            $item->generateData();
        }

        $data = [
            "title" => [
                "highlight" => "Tours",
                "normal" => "khởi hành trong tháng"
            ],
            "data" => $tours
        ];
        return $data;
    }

    public function getPopularPlaces()
    {
        $places = Place::where('is_hot', ACTIVE)->where('type', INTERNATIONAL)->orderBy('index', 'desc')->limit(7)->get();
        foreach ($places as $item) {
            $item->generateData();
        }

        $data = [
            "title" => [
                "highlight" => "Điểm đến quốc tế",
                "normal" => "phổ biến"
            ],
            "data" => $places
        ];

        return $data;
    }

    public function getPageContent($slug = 'gioi-thieu')
    {
        $page = Page::where('slug', $slug)->first();

        return $page->content;
    }

    public function getPageData($slug = 'gioi-thieu')
    {
        $page = Page::where('slug', $slug)->first();

        return $page;
    }

    public function getPageImages()
    {
        $imageModels = Image::select('origin', 'width_ratio', 'height_ratio')->orderBy('id', 'desc')->get();
        $images = [];

        foreach ($imageModels as $model) {
            $images[] = array(
                'src' => asset('storage/' . $model->origin),
                'width' => ($model->width_ratio) ? $model->width_ratio : rand(1,5),
                'height' => ($model->height_ratio) ? $model->height_ratio : rand(1,5)
            );
        }

        shuffle($images);

        $data = [
            "banner" => $this->getBanner('about'),
            "images" => $images,
        ];
        return $data;
    }
}
