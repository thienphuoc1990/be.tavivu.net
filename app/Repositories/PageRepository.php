<?php

namespace App\Repositories;

use App\Models\Page;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use Response;

Class PageRepository
{
    public function dataTable($request)
    {
        $pages = Page::select(['pages.id', 'pages.title']);

        $dataTable = Datatables::eloquent($pages)
        ->filter(function ($query) use ($request) {
            if (trim($request->get('keyword')) !== "") {
                $query->where(function ($sub) use ($request) {
                    $sub->where('pages.title', 'like', '%' . $request->get('keyword') . '%');
                });
            }
        }, true)
        ->addColumn('action', function ($page) {
            $html = '';
            $html .= '<a href="' . route('admin.pages.edit', ['page' => $page->id]) . '" class="btn btn-xs btn-primary" style="margin-right: 5px"><i class="glyphicon glyphicon-edit"></i> Sửa</a>';
            $html .= '<a href="#" class="bt-delete btn btn-xs btn-danger" data-id="' . $page->id . '" data-name="' . $page->title . '">';
            $html .= '<i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a>';
            return $html;
        })
        ->rawColumns(['action'])
        ->toJson();

        return $dataTable;
    }

    public function createOrUpdate($data, $id = null) {
        if ($id) {
            $model = Page::find($id);
        } else {
            $model = new Page;
        }

        $model->title = $data['title'];
        $model->slug = str_slug($model->title, '-');
        $model->content = $data['content'];

        $model->save();
    }

    public function getPage($id) {
        $page = Page::find($id);
        return $page;
    }
}
