<?php

namespace App\Repositories\API;

use Illuminate\Support\Facades\Cache;
use Response;
use Mail;
use Carbon\Carbon;

use App\Models\News;

use App\Repositories\API\BaseRepository;
use App\Mail\SendNewsOrder;

class NewsRepository extends BaseRepository
{
    public function getNews($id)
    {
        $data = Cache::remember('get-news'.$id, 22 * 60, function () use ($id) {
            $data = [
                "banner" => $this->getBanner('news-detail', $id),
                "news" => $this->getNewsData($id)
            ];
            foreach($data['banner']['slides'] as $key => $slide) {
                $data['banner']['slides'][$key]['title'] = $data['news']->title;
            }
            return $data;
        });
        return $data;
    }

    public function getNewsList()
    {
        $data = Cache::remember('get-news-list', 22 * 60, function () {
            $data = [
                "banner" => $this->getBanner('news-list'),
                "news" => $this->getNewsListData()
            ];
            return $data;
        });
        return $data;
    }

    public function getNewsData($id)
    {
        $news = News::find($id);
        $news->generateData();

        return $news;
    }

    public function getNewsListData()
    {
        $news = News::select('id', 'title', 'image', 'thumb')->where('active', ACTIVE)->get();
        foreach ($news as $item) {
            $item->generateData();
        }

        return $news;
    }
}
