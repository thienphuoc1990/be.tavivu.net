<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;

use App\Http\Controllers\Admin\BaseController;
use App\Repositories\ServiceRepository;

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

        $this->_pushBreadCrumbs('Danh sách yêu cầu dịch vụ', route('admin.services.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  App\Repositories\ServiceRepository  $service
     * @return \Illuminate\Http\Response
     */
    public function index(ServiceRepository $service)
    {
        if ($this->request->ajax()){
            return $service->dataTable($this->request);
        }

        $this->data['title'] = 'Service';
        return view('admin.services.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ServiceRepository $service)
    {
        $this->data['title'] = 'Add new service';
        $this->data['type_options'] = $service->makeServiceTypeOptions();
        return view('admin.services.detail', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Repositories\ServiceRepository  $service
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceRepository $service)
    {
        $input = $this->request->all();
        $id = $input['id'] ?? null;

        $message = 'Service đã được tạo.';

        if ($id) {
            $message = 'Service đã được cập nhật.';
        }

        $data = $service->createOrUpdate($input, $id);

        return redirect()->route('admin.services.index')->withSuccess($message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Repositories\ServiceRepository  $service
     * @return \Illuminate\Http\Response
     */
    public function edit($id, ServiceRepository $service)
    {
        $this->data['data'] = $service->getService($id);
        $this->data['title'] = 'Edit service';
        $this->data['type_options'] = $service->makeServiceTypeOptions($this->data['data']->type);
        return view('admin.services.detail', $this->data);
    }
}
