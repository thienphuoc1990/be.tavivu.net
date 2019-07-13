<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\API\BaseRepository;

class BaseController extends Controller
{
    protected $_data;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getConfigs(BaseRepository $base) {
        $this->data['data'] = $base->getConfigs();

        return response()->json($this->data, 200);
    }
}
