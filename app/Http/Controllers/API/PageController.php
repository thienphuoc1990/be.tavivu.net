<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Repositories\API\PageRepository;

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
    }

    /**
     * Display a listing of the resource.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function index(PageRepository $page)
    {
        $this->data['title'] = 'Home page';
        $this->data['data'] = $page->getHome();

        return response()->json($this->data, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function about(PageRepository $page)
    {
        $this->data['title'] = 'About';
        $this->data['data'] = $page->getAbout();

        return response()->json($this->data, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function toursInCountry(PageRepository $page)
    {
        $this->data['title'] = 'Tours trong nước';
        $this->data['data'] = $page->getToursInCountry($this->request);

        return response()->json($this->data, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function toursInternational(PageRepository $page)
    {
        $this->data['title'] = 'Tours quốc tế';
        $this->data['data'] = $page->getToursInternational($this->request);

        return response()->json($this->data, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function contact(PageRepository $page)
    {
        $this->data['title'] = 'Liên hệ';
        $this->data['data'] = $page->getContact();

        return response()->json($this->data, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function page(PageRepository $page, $page_slug)
    {
        $this->data['data'] = $page->getPage($page_slug);
        $this->data['title'] = $this->data['data']['title'];

        return response()->json($this->data, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function images(PageRepository $page)
    {
        $this->data['title'] = 'Gallery';
        $this->data['data'] = $page->getPageImages();

        return response()->json($this->data, 200);
    }
}
