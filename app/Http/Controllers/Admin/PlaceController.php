<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;

use App\Http\Controllers\Admin\BaseController;
use App\Repositories\PlaceRepository;

class PlaceController extends BaseController
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->_pushBreadCrumbs('Danh sách địa điểm', route('admin.places.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  App\Repositories\PlaceRepository  $place
     * @return \Illuminate\Http\Response
     */
    public function index(PlaceRepository  $place)
    {
        if ($this->request->ajax()){
            return $place->dataTable($this->request);
        }

        $this->data['title'] = 'Place';
        return view('admin.places.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  App\Repositories\PlaceRepository  $place
     * @return \Illuminate\Http\Response
     */
    public function create(PlaceRepository $place)
    {
        $this->data['title'] = 'Add new place';
        $this->data['is_hot_options'] = $place->makeIsHotOptions();
        $this->data['type_options'] = $place->makeTourTypeOptions();
        return view('admin.places.detail', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Repositories\PlaceRepository  $place
     * @return \Illuminate\Http\Response
     */
    public function store(PlaceRepository $place)
    {
        $input = $this->request->all();
        $id = $input['id'] ?? null;

        $rules = [
            'title' => 'required|string|unique:places,title',
        ];
        $message = 'Place đã được tạo.';

        if ($id) {
            $rules['title'] = 'required|string|unique:places,title,' . $input['id'];
            $message = 'Place đã được cập nhật.';
        }

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $place->createOrUpdate($input, $id);

        return redirect()->route('admin.places.index')->withSuccess($message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Repositories\PlaceRepository  $place
     * @return \Illuminate\Http\Response
     */
    public function edit($id, PlaceRepository $place)
    {
        $this->data['data'] = $place->getPlace($id);
        $this->data['title'] = 'Edit place';
        $this->data['is_hot_options'] = $place->makeIsHotOptions($this->data['data']->is_hot);
        $this->data['type_options'] = $place->makeTourTypeOptions($this->data['data']->type);
        return view('admin.places.detail', $this->data);
    }
}
