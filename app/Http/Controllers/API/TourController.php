<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Repositories\API\TourRepository;

class TourController extends BaseController
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Display a listing of the resource.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function view($id, TourRepository $tour)
    {
        $this->data['data'] = $tour->getTour($id);
        $this->data['title'] = $this->data['data']['tour']['title'];

        return response()->json($this->data, 200);
    }



    public function sendTourOrder(TourRepository $tour)
    {
        $result = [
            "code" => "SEND_SU",
            "message" => "Order Tour successfull"
        ];

        $tour->sendTourOrder($this->request);

        return response()->json($result, 200);
    }
}
