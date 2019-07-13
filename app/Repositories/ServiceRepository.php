<?php

namespace App\Repositories;

use App\Models\Service;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use Response;

Class ServiceRepository extends BaseRepository
{
    public function dataTable($request)
    {
        $services = Service::select(['services.id', 'services.name', 'services.email', 'services.phone', 'services.type']);

        $dataTable = Datatables::eloquent($services)
        ->filter(function ($query) use ($request) {
            if (trim($request->get('keyword')) !== "") {
                $query->where(function ($sub) use ($request) {
                    $sub->where('services.name', 'like', '%' . $request->get('keyword') . '%');
                });
            }
        }, true)
        ->addColumn('action', function ($service) {
            $html = '';
            $html .= '<a href="' . route('admin.services.edit', ['service' => $service->id]) . '" class="btn btn-xs btn-primary" style="margin-right: 5px"><i class="glyphicon glyphicon-edit"></i> Sửa</a>';
            $html .= '<a href="#" class="bt-delete btn btn-xs btn-danger" data-id="' . $service->id . '" data-name="' . $service->title . '">';
            $html .= '<i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a>';
            return $html;
        })
        ->addColumn('type', function ($service) {
            return SERVICE_TYPE_TEXT[$service->type];
        })
        ->rawColumns(['action', 'type'])
        ->toJson();

        return $dataTable;
    }

    public function createOrUpdate($data, $id = null) {
        if ($id) {
            $model = Service::find($id);
        } else {
            $model = new Service;
        }

        $model->name = $data['name'];
        $model->phone = $data['phone'];
        $model->email = $data['email'];
        $model->type = $data['type'];
        $model->note = $data['note'];

        $model->save();
    }

    public function getService($id) {
        $service = Service::find($id);
        return $service;
    }
}
