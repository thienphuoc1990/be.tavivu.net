<?php

namespace App\Repositories;

use App\Models\News;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Libraries\Photo;
use Response;

use App\Repositories\BaseRepository;

Class NewsRepository extends BaseRepository
{
    public function dataTable($request)
    {
        $news = News::select(['news.id', 'news.title', 'news.thumb', 'news.is_hot', 'news.active']);

        $dataTable = Datatables::eloquent($news)
        ->filter(function ($query) use ($request) {
            if (trim($request->get('keyword')) !== "") {
                $query->where(function ($sub) use ($request) {
                    $sub->where('news.title', 'like', '%' . $request->get('keyword') . '%');
                });
            }
        }, true)
        ->addColumn('action', function ($news) {
            $html = '';
            $html .= '<a href="' . route('admin.news.edit', ['news' => $news->id]) . '" class="btn btn-xs btn-primary" style="margin-right: 5px"><i class="glyphicon glyphicon-edit"></i> Sửa</a>';
            $html .= '<a href="#" class="bt-delete btn btn-xs btn-danger" data-id="' . $news->id . '" data-name="' . $news->title . '">';
            $html .= '<i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a>';
            return $html;
        })
        ->addColumn('image', function ($news) {
            $html = '<img src="'.asset('storage/' .$news->thumb).'" />';
            return $html;
        })
        ->addColumn('is_hot', function ($news) {
            $html = '';
            $class = "badge-primary";

            if ($news->is_hot == INACTIVE) {
                $class = "badge-secondary";
            }

            $html = '<h5><span class="badge '.$class.'">'.HOT_TEXT[$news->is_hot].'</span></h5>';

            return $html;
        })
        ->addColumn('active', function ($news) {
            $html = '';
            $class = "badge-primary";

            if ($news->active == INACTIVE) {
                $class = "badge-secondary";
            }

            $html = '<h5><span class="badge '.$class.'">'.ACTIVE_TEXT[$news->active].'</span></h5>';

            return $html;
        })
        ->rawColumns(['action', 'image', 'is_hot', 'active'])
        ->toJson();

        return $dataTable;
    }

    public function createOrUpdate($data, $id = null) {
        if ($id) {
            $model = News::find($id);
        } else {
            $model = new News;
        }

        $model->title = $data['title'];
        $model->slug = str_slug($model->title, '-');
        $model->ascii_title = vn_to_str($data['title']);
        $model->content = $data['content'];
        $model->is_hot = $data['is_hot'];
        $model->index = $data['index'];
        $model->active = $data['active'];

        if (isset($data['image'])) {
            if ($model->image) {
                Storage::delete('public/' .$model->image);
            }

            if ($model->thumb) {
                Storage::delete('public/' .$model->thumb);
            }

            $image = $data['image'];
            $upload = new Photo($image);
            $model->image = str_replace('public/', '', $upload->uploadTo('news'));
            $model->thumb = str_replace('public/', '', $upload->resizeTo(500));
        }

        $model->save();
    }

    public function getNews($id) {
        $news = News::find($id);
        return $news;
    }
}
