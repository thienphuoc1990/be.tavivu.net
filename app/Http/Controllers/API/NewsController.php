<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Repositories\API\NewsRepository;

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
    }

    /**
     * Send a listing of the resource.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function index(NewsRepository $news)
    {
        $this->data['data'] = $news->getNewsList();
        $this->data['title'] = 'Bài viết';

        return response()->json($this->data, 200);
    }

    /**
     * Send a detail of the resource.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function view($id, NewsRepository $news)
    {
        $this->data['data'] = $news->getNews($id);
        $this->data['title'] = $this->data['data']['news']['title'];

        return response()->json($this->data, 200);
    }
}
