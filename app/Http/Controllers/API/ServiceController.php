<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Repositories\API\ServiceRepository;

class ServiceController extends BaseController
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

    public function sendService(ServiceRepository $service)
    {
        $result = [
            "code" => "SEND_SU",
            "message" => "Send service successfull"
        ];

        $service->sendService($this->request);

        return response()->json($result, 200);
    }
}
