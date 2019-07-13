<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;

use App\Http\Controllers\Admin\BaseController;
use App\Repositories\ImageRepository;

class ImageController extends BaseController
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->_pushBreadCrumbs('Danh sách hình ảnh', route('admin.images.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  App\Repositories\ImageRepository  $image
     * @return \Illuminate\Http\Response
     */
    public function index(ImageRepository $image)
    {
        if ($this->request->ajax()){
            $result = [
                "status" => true,
                "message" => "Load Images success"
            ];

            $result['images'] = $image->getImages($this->request->all());

            return response()->json($result, 200);;
        }

        $this->data['title'] = 'Images';
        return view('admin.images.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['title'] = 'Add new image';
        return view('admin.images.detail', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Repositories\ImageRepository  $image
     * @return \Illuminate\Http\Response
     */
    public function store(ImageRepository $image)
    {
        $input = $this->request->all();
        $id = $input['id'] ?? null;

        $message = 'Image đã được tạo.';

        if ($id) {
            $message = 'Image đã được cập nhật.';
        }

        $data = $image->createOrUpdate($input, $id);

        return redirect()->route('admin.images.index')->withSuccess($message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Repositories\ImageRepository  $image
     * @return \Illuminate\Http\Response
     */
    public function edit($id, ImageRepository $image)
    {
        $this->data['data'] = $image->getImage($id);
        $this->data['title'] = 'Edit Image';
        return view('admin.images.detail', $this->data);
    }

    public function destroy(ImageRepository $image)
    {
        $id = $this->request->get('id');
        $result = $image->destroy($id);

        return response()->json($result);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  App\Repositories\ImageRepository  $image
     * @return \Illuminate\Http\Response
     */
    public function upload(ImageRepository $image)
    {
        if ($this->request->ajax()){
            $rules = [
                'title' => 'required',
                'file' => 'image',
                'file.image' => 'The file must be an image (jpeg, png, bmp, gif, or svg)'
            ];

            $validator = Validator::make($this->request->all(),$rules);

            if ($validator->fails())
                return array(
                    'request' => $this->request->all(),
                    'fail' => true,
                    'errors' => $validator->errors()
                );

            $result = $image->uploadImage($this->request);

            return response()->json($result, 200);
        }
    }
}
