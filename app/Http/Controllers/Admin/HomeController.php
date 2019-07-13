<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;

use App\Http\Controllers\Admin\BaseController;
use App\Repositories\PageRepository;

class HomeController extends BaseController
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->_pushBreadCrumbs('Dashboard', route('admin.pages.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['title'] = 'Dashboard';
        return view('admin.index', $this->data);
    }
}
