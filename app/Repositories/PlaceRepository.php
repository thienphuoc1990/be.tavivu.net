<?php

namespace App\Repositories;

use App\Models\Place;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use App\Libraries\Photo;
use Illuminate\Support\Facades\Storage;
use Response;

use App\Repositories\BaseRepository;

class PlaceRepository extends BaseRepository
{
    public function dataTable($request)
    {
        $places = Place::select(['places.id', 'places.title', 'places.thumb', 'places.is_hot']);

        $dataTable = Datatables::eloquent($places)
            ->filter(function ($query) use ($request) {
                if (trim($request->get('keyword')) !== "") {
                    $query->where(function ($sub) use ($request) {
                        $sub->where('places.title', 'like', '%' . $request->get('keyword') . '%');
                    });
                }
            }, true)
            ->addColumn('action', function ($place) {
                $html = '';
                $html .= '<a href="' . route('admin.places.edit', ['place' => $place->id]) . '" class="btn btn-xs btn-primary" style="margin-right: 5px"><i class="glyphicon glyphicon-edit"></i> Sửa</a>';
                $html .= '<a href="#" class="bt-delete btn btn-xs btn-danger" data-id="' . $place->id . '" data-name="' . $place->title . '">';
                $html .= '<i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a>';
                return $html;
            })
            ->addColumn('image', function ($place) {
                $html = '<img src="'.asset('storage/' .$place->thumb).'" />';
                return $html;
            })
            ->addColumn('is_hot', function ($place) {
                $html = '';

                if ($place->is_hot == ACTIVE) {
                    $html = '<h5><span class="badge badge-primary">Hot</span></h5>';
                }else{
                    $html = '<h5><span class="badge badge-secondary">Normal</span></h5>';
                }

                return $html;
            })
            ->rawColumns(['action', 'image', 'is_hot'])
            ->toJson();

        return $dataTable;
    }

    public function createOrUpdate($data, $id = null)
    {
        if ($id) {
            $model = Place::find($id);
        } else {
            $model = new Place;
        }

        $model->title = $data['title'];
        $model->ascii_title = vn_to_str($data['title']);
        $model->latitude = $data['latitude'];
        $model->longitude = $data['longitude'];
        $model->description = $data['description'];
        $model->attractions = $data['attractions'];
        $model->is_hot = $data['is_hot'];
        $model->index = $data['index'];
        $model->type = $data['type'];

        if (isset($data['image'])) {
            if ($model->image) {
                Storage::delete('public/' .$model->image);
            }

            if ($model->thumb) {
                Storage::delete('public/' .$model->thumb);
            }

            $image = $data['image'];
            $upload = new Photo($image);
            $model->image = str_replace('public/', '', $upload->uploadTo('places'));
            $model->thumb = str_replace('public/', '', $upload->resizeTo(500));
        }

        $model->save();
    }

    public function getPlace($id)
    {
        $place = Place::find($id);
        return $place;
    }
}
