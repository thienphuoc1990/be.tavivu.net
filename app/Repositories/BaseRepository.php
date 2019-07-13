<?php

namespace App\Repositories;

class BaseRepository
{
    public function makeIsActiveOptions($id = 0)
    {
        $data = [
            [
                "id" => ACTIVE,
                "name" => ACTIVE_TEXT[ACTIVE]
            ],
            [
                "id" => INACTIVE,
                "name" => ACTIVE_TEXT[INACTIVE]
            ]
        ];
        return make_option($data, $id);
    }

    public function makeIsComingOptions($id = 0)
    {
        $data = [
            [
                "id" => ACTIVE,
                "name" => COMING_TEXT[ACTIVE]
            ],
            [
                "id" => INACTIVE,
                "name" => COMING_TEXT[INACTIVE]
            ]
        ];
        return make_option($data, $id);
    }

    public function makeIsHotOptions($id = 0)
    {
        $data = [
            [
                "id" => ACTIVE,
                "name" => HOT_TEXT[ACTIVE]
            ],
            [
                "id" => INACTIVE,
                "name" => HOT_TEXT[INACTIVE]
            ]
        ];
        return make_option($data, $id);
    }

    public function makeTourTypeOptions($id = 0)
    {
        $data = [
            [
                "id" => IN_COUNTRY,
                "name" => TOUR_TYPE_TEXT[IN_COUNTRY]
            ],
            [
                "id" => INTERNATIONAL,
                "name" => TOUR_TYPE_TEXT[INTERNATIONAL]
            ]
        ];
        return make_option($data, $id);
    }

    public function makeServiceTypeOptions($id = 0)
    {
        $data = [
            [
                "id" => VISA,
                "name" => SERVICE_TYPE_TEXT[VISA]
            ],
            [
                "id" => AIRPORT_CAR,
                "name" => SERVICE_TYPE_TEXT[AIRPORT_CAR]
            ],
            [
                "id" => HOTEL,
                "name" => SERVICE_TYPE_TEXT[HOTEL]
            ],
            [
                "id" => FLIGHT_TICKET,
                "name" => SERVICE_TYPE_TEXT[FLIGHT_TICKET]
            ]
        ];
        return make_option($data, $id);
    }

    public function makeTourOrderStatusOptions($id = 0)
    {
        $data = [
            [
                "id" => WAITING,
                "name" => TOUR_ORDER_STATUS_TEXT[WAITING]
            ],
            [
                "id" => ACCEPTED,
                "name" => TOUR_ORDER_STATUS_TEXT[ACCEPTED]
            ]
        ];
        return make_option($data, $id);
    }
}
