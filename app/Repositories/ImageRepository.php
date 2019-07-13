<?php

namespace App\Repositories;

use App\Models\Image;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Libraries\Photo;
use Response;

Class ImageRepository
{
    public function dataTable($request)
    {
        $images = Image::select(['images.id', 'images.name', 'images.email', 'images.phone', 'images.address']);

        $dataTable = Datatables::eloquent($images)
        ->filter(function ($query) use ($request) {
            if (trim($request->get('keyword')) !== "") {
                $query->where(function ($sub) use ($request) {
                    $sub->where('images.name', 'like', '%' . $request->get('keyword') . '%');
                });
            }
        }, true)
        ->addColumn('action', function ($image) {
            $html = '';
            $html .= '<a href="' . route('admin.images.edit', ['image' => $image->id]) . '" class="btn btn-xs btn-primary" style="margin-right: 5px"><i class="glyphicon glyphicon-edit"></i> Sửa</a>';
            $html .= '<a href="#" class="bt-delete btn btn-xs btn-danger" data-id="' . $image->id . '" data-name="' . $image->name . '">';
            $html .= '<i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a>';
            return $html;
        })
        ->rawColumns(['action'])
        ->toJson();

        return $dataTable;
    }

    public function createOrUpdate($data, $id = null) {
        if ($id) {
            $model = Image::find($id);
        } else {
            $model = new Image;
        }

        $model->name = $data['name'];
        $model->phone = $data['phone'];
        $model->email = $data['email'];
        $model->address = $data['address'];
        $model->content = $data['content'];

        $model->save();
    }

    public function getImage($id) {
        $image = Image::find($id);
        return $image;
    }

    public function destroy($id)
    {
        $result = [
            'success' => true,
            'errors' => []
        ];
        $model = Image::find($id);

        if ($model === null) {
            $result['errors'][] = 'ID này ' . $id . ' không tồn tại';
            $result['success'] = false;
        }

        if ($model->origin) {
            Storage::delete('public/' . $model->origin);
        }

        if ($model->thumb) {
            Storage::delete('public/' . $model->thumb);
        }

        if ($model->large) {
            Storage::delete('public/' . $model->large);
        }
        $model->delete();

        return $result;
    }

    public function uploadImage($request) {
        $image = $request->file('file');

        $model = new Image;
        $upload = new Photo($image);

        $data = getimagesize($image);
        $tmp = get_aspect_ratio($data[0],$data[1]);
        $tmps = explode(":", $tmp);
        $model->width_ratio = $tmps[0];
        $model->height_ratio = $tmps[1];

        $model->title = $request->get('title');
        $model->description = $request->get('description');

        $name_slug = str_slug($model->title, '-');

        $name = $name_slug .'_'. time();

        $model->origin = str_replace('public/', '', $upload->uploadTo('images', $name ));
        $model->thumb = str_replace('public/', '', $upload->resizeTo_v2(500, $name ));

        $model->save();

        $return = array(
            'fail' => false,
            'file_name' => $model->origin
        );

        return $return;
    }

    public function getImages($data) {
        $model = Image::orderBy('id', 'desc')->get();

        return $model;
    }
}
