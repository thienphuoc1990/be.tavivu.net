<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;

use App\Http\Controllers\Admin\BaseController;
use App\Repositories\TourOrderRepository;

class TourOrderController extends BaseController
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->_pushBreadCrumbs('Danh sách đặt tour', route('admin.tour_orders.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  App\Repositories\TourOrderRepository  $tour_order
     * @return \Illuminate\Http\Response
     */
    public function index(TourOrderRepository $tour_order)
    {
        if ($this->request->ajax()){
            return $tour_order->dataTable($this->request);
        }

        $this->data['title'] = 'Tour Order';
        return view('admin.tour_orders.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(TourOrderRepository $tour_order)
    {
        $this->data['title'] = 'Add new tour order';
        $this->data['status_options'] = $tour_order->makeTourOrderStatusOptions();
        return view('admin.tour_orders.detail', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Repositories\TourOrderRepository  $tour_order
     * @return \Illuminate\Http\Response
     */
    public function store(TourOrderRepository $tour_order)
    {
        $input = $this->request->all();
        $id = $input['id'] ?? null;

        $message = 'Tour Order đã được tạo.';

        if ($id) {
            $message = 'Tour Order đã được cập nhật.';
        }

        $data = $tour_order->createOrUpdate($input, $id);

        return redirect()->route('admin.tour_orders.index')->withSuccess($message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Repositories\TourOrderRepository  $tour_order
     * @return \Illuminate\Http\Response
     */
    public function edit($id, TourOrderRepository $tour_order)
    {
        $this->data['data'] = $tour_order->getTourOrder($id);
        $this->data['title'] = 'Edit Tour Order';
        $this->data['status_options'] = $tour_order->makeTourOrderStatusOptions($this->data['data']->status);
        return view('admin.tour_orders.detail', $this->data);
    }
}
