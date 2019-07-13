<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;

use App\Http\Controllers\Admin\BaseController;
use App\Repositories\PageRepository;

class PageController extends BaseController
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->_pushBreadCrumbs('Danh sách trang', route('admin.pages.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  App\Repositories\PageRepository  $page
     * @return \Illuminate\Http\Response
     */
    public function index(PageRepository $page)
    {
        if ($this->request->ajax()){
            return $page->dataTable($this->request);
        }

        $this->data['title'] = 'Page';
        return view('admin.pages.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['title'] = 'Add new page';
        return view('admin.pages.detail', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Repositories\PageRepository  $page
     * @return \Illuminate\Http\Response
     */
    public function store(PageRepository $page)
    {
        $input = $this->request->all();
        $id = $input['id'] ?? null;

        $rules = [
            'title' => 'required|string|unique:pages,title',
        ];
        $message = 'Page đã được tạo.';

        if ($id) {
            $rules['title'] = 'required|string|unique:pages,title,' . $input['id'];
            $message = 'Page đã được cập nhật.';
        }

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $page->createOrUpdate($input, $id);

        return redirect()->route('admin.pages.index')->withSuccess($message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Repositories\PageRepository  $page
     * @return \Illuminate\Http\Response
     */
    public function edit($id, PageRepository $page)
    {
        $this->data['data'] = $page->getPage($id);
        $this->data['title'] = 'Edit page';
        return view('admin.pages.detail', $this->data);
    }
}
