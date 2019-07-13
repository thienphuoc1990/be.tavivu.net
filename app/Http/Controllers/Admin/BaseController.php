<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

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
        $this->middleware('auth');
        $this->data['auth'] = Auth::user();
        $this->data['breadcrumbs'] = [];
        $this->request = $request;
    }

    protected function _pushBreadCrumbs($name, $link = null)
    {
        $data['name'] = $name;
        if ($link) $data['link'] = $link;
        array_push($this->data['breadcrumbs'], $data);
    }
}
