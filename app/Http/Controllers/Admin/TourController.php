<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;

use App\Http\Controllers\Admin\BaseController;
use App\Repositories\TourRepository;

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

        $this->_pushBreadCrumbs('Danh sách tour', route('admin.tours.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  App\Repositories\TourRepository  $tour
     * @return \Illuminate\Http\Response
     */
    public function index(TourRepository $tour)
    {
        if ($this->request->ajax()){
            return $tour->dataTable($this->request);
        }

        $this->data['title'] = 'Tour';
        return view('admin.tours.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(TourRepository  $tour)
    {
        $this->data['title'] = 'Add new tour';
        $this->data['type_options'] = $tour->makeTourTypeOptions();
        $this->data['is_hot_options'] = $tour->makeIsHotOptions();
        $this->data['is_coming_options'] = $tour->makeIsComingOptions();
        $this->data['is_active_options'] = $tour->makeIsActiveOptions();
        return view('admin.tours.detail', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Repositories\TourRepository  $tour
     * @return \Illuminate\Http\Response
     */
    public function store(TourRepository $tour)
    {

        $input = $this->request->all();
        $id = $input['id'] ?? null;

        $rules = [
            'title' => 'required|string|unique:tours,title'
        ];
        $message = 'Tour đã được tạo.';

        if ($id) {
            $rules['title'] = 'required|string|unique:tours,title,' . $input['id'];
            $message = 'Tour đã được cập nhật.';
        }

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $tour->createOrUpdate($input, $id);

        return redirect()->route('admin.tours.index')->withSuccess($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Repositories\TourRepository  $tour
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Repositories\TourRepository  $tour
     * @return \Illuminate\Http\Response
     */
    public function edit($id, TourRepository $tour)
    {
        $this->data['data'] = $tour->getTour($id);
        $this->data['title'] = 'Edit tour';
        $this->data['type_options'] = $tour->makeTourTypeOptions($this->data['data']->type);
        $this->data['is_hot_options'] = $tour->makeIsHotOptions($this->data['data']->is_hot);
        $this->data['is_coming_options'] = $tour->makeIsComingOptions($this->data['data']->is_coming);
        $this->data['is_active_options'] = $tour->makeIsActiveOptions($this->data['data']->active);
        return view('admin.tours.detail', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getPlaces(TourRepository $tour) {
        $data = $tour->getPlaces($this->request);
        $message = 'Không tìm thấy thành phố nào';
        if (count($data)) {
            $message = 'Thành phố được lấy thành công.';
        }
        return response()->json($data);
    }

    public function getTours(TourRepository $tour) {
        $data = $tour->getTours($this->request);
        $message = 'Không tìm thấy tour nào';
        if (count($data)) {
            $message = 'Tour được lấy thành công.';
        }
        return response()->json($data);
    }

    public function getTourDetails(TourRepository $tour) {
        $data = $tour->getTourDetails($this->request);
        $message = 'Không tìm thấy chuyến nào của tour này';
        if (count($data)) {
            $message = 'Chuyến tour được lấy thành công.';
        }
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addSchedule($tour_id)
    {
        $this->data['title'] = 'Add new schedule';
        $this->data['tour_id'] = $tour_id;
        return view('admin.tours.schedules.detail', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Repositories\TourRepository  $tour
     * @return \Illuminate\Http\Response
     */
    public function storeSchedule(TourRepository $tour)
    {
        $input = $this->request->all();
        $id = $input['id'] ?? null;

        $rules = [
            'title' => 'required|string|unique:tour_schedules,title'
        ];
        $message = 'Tour schedule đã được tạo.';

        if ($id) {
            $rules['title'] = 'required|string|unique:tour_schedules,title,' . $input['id'];
            $message = 'Tour schedule đã được cập nhật.';
        }

        // $validator = Validator::make($input, $rules);
        // if ($validator->fails()) {

        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        $data = $tour->createOrUpdateSchedule($input, $id);

        return redirect()->route('admin.tours.index')->withSuccess($message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Repositories\TourRepository  $tour
     * @return \Illuminate\Http\Response
     */
    public function editSchedule($tour_id, $id, TourRepository $tour)
    {
        $this->data['tour_id'] = $tour_id;
        $this->data['data'] = $tour->getTourSchedule($id);
        $this->data['title'] = 'Edit tour schedule';
        return view('admin.tours.schedules.detail', $this->data);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  App\Repositories\TourRepository  $tour
     * @return \Illuminate\Http\Response
     */
    public function indexSchedule($tour_id, TourRepository $tour)
    {
        if ($this->request->ajax()){
            return $tour->dataTableSchedules($tour_id, $this->request);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addDetail($tour_id, TourRepository $tour)
    {
        $this->data['title'] = 'Add new tour detail';
        $this->data['tour_id'] = $tour_id;
        $this->data['is_active_options'] = $tour->makeIsActiveOptions();
        return view('admin.tours.details.detail', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Repositories\TourRepository  $tour
     * @return \Illuminate\Http\Response
     */
    public function storeDetail(TourRepository $tour)
    {
        $input = $this->request->all();
        $id = $input['id'] ?? null;
        $tour_id = $input['tour_id'] ?? null;

        $rules = [
            'title' => 'required|string|unique:tour_schedules,title'
        ];
        $message = 'Tour chi tiết đã được tạo.';

        if ($id) {
            $rules['title'] = 'required|string|unique:tour_schedules,title,' . $input['id'];
            $message = 'Tour chi tiết đã được cập nhật.';
        }

        // $validator = Validator::make($input, $rules);
        // if ($validator->fails()) {

        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        $data = $tour->createOrUpdateDetail($input, $id);

        if($input['action'] === 'save_continue') {
            return redirect()->route('admin.tours.details.add', ['tour' => $tour_id]);
        }

        return redirect()->route('admin.tours.index')->withSuccess($message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Repositories\TourRepository  $tour
     * @return \Illuminate\Http\Response
     */
    public function editDetail($tour_id, $id, TourRepository $tour)
    {
        $this->data['tour_id'] = $tour_id;
        $this->data['data'] = $tour->getTourDetail($id);
        $this->data['title'] = 'Edit tour detail';
        $this->data['is_active_options'] = $tour->makeIsActiveOptions($this->data['data']->active);
        return view('admin.tours.details.detail', $this->data);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  App\Repositories\TourRepository  $tour
     * @return \Illuminate\Http\Response
     */
    public function indexDetail($tour_id, TourRepository $tour)
    {
        if ($this->request->ajax()){
            return $tour->dataTableDetails($tour_id, $this->request);
        }
    }
}
