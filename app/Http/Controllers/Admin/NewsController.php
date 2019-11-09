<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;

use App\Http\Controllers\Admin\BaseController;
use App\Repositories\NewsRepository;

class NewsController extends BaseController
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->_pushBreadCrumbs('Danh sách tin tức', route('admin.news.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  App\Repositories\NewsRepository  $news
     * @return \Illuminate\Http\Response
     */
    public function index(NewsRepository $news)
    {
        if ($this->request->ajax()){
            return $news->dataTable($this->request);
        }

        $this->data['title'] = 'News';
        return view('admin.news.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(NewsRepository $news)
    {
        $this->data['title'] = 'Add new news';
        $this->data['is_hot_options'] = $news->makeIsHotOptions();
        $this->data['active_options'] = $news->makeIsActiveOptions();
        return view('admin.news.detail', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Repositories\NewsRepository  $news
     * @return \Illuminate\Http\Response
     */
    public function store(NewsRepository $news)
    {
        $input = $this->request->all();
        $id = $input['id'] ?? null;

        $rules = [
            'title' => 'required|string|unique:news,title',
        ];
        $message = 'News đã được tạo.';

        if ($id) {
            $rules['title'] = 'required|string|unique:news,title,' . $input['id'];
            $message = 'News đã được cập nhật.';
        }

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $news->createOrUpdate($input, $id);

        return redirect()->route('admin.news.index')->withSuccess($message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Repositories\NewsRepository  $news
     * @return \Illuminate\Http\Response
     */
    public function edit($id, NewsRepository $news)
    {
        $this->data['data'] = $news->getNews($id);
        $this->data['is_hot_options'] = $news->makeIsHotOptions($this->data['data']->is_hot);
        $this->data['active_options'] = $news->makeIsActiveOptions($this->data['data']->active);
        $this->data['title'] = 'Edit news';
        return view('admin.news.detail', $this->data);
    }
}
