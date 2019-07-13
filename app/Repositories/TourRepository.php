<?php

namespace App\Repositories;

use App\Models\Tour;
use App\Models\TourSchedule;
use App\Models\TourDetail;
use App\Models\TourImage;
use App\Models\TourBanner;
use App\Models\TourScheduleImage;
use App\Models\Place;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use App\Libraries\Photo;
use Illuminate\Support\Facades\Storage;
use Response;

use App\Repositories\BaseRepository;

class TourRepository extends BaseRepository
{
    public function getTour($id)
    {
        $tour = Tour::find($id);
        $tour->images_rel;

        $from_place = [];
        $to_place = [];
        foreach (explode(',', str_replace('-', '', $tour->from_place)) as $item) {
            $place = Place::find($item);
            $from_place[] = $place;
        }

        foreach (explode(',', str_replace('-', '', $tour->to_place)) as $item) {
            $place = Place::find($item);
            $to_place[] = $place;
        }

        $tour->from_place = $from_place;
        $tour->to_place = $to_place;

        return $tour;
    }

    public function getTourSchedule($id)
    {
        $tour_schedule = TourSchedule::find($id);
        $tour_schedule->images;
        return $tour_schedule;
    }

    public function getTourDetail($id)
    {
        $tour_detail = TourDetail::find($id);
        $tour_detail->images;
        return $tour_detail;
    }

    public function createOrUpdate($data, $id = null)
    {
        if ($id) {
            $model = Tour::find($id);
        } else {
            $model = new Tour;
        }

        $model->title = $data['title'];
        $model->sub_title = $data['sub_title'];
        $model->type = $data['type'];
        $model->time_range = $data['time_range'];
        $model->vehicle = $data['vehicle'];
        $model->from_place = '-' . implode("-,-", $data['from_place']) . '-';
        $model->to_place = '-' . implode("-,-", $data['to_place']) . '-';
        $model->tour_attractions = $data['tour_attractions'];
        $model->tour_policies = $data['tour_policies'];
        $model->is_hot = $data['is_hot'];
        $model->is_coming = $data['is_coming'];
        $model->active = $data['active'];
        $model->index = $data['index'];

        $model->save();

        if (isset($data['images'])) {
            foreach ($data['images'] as $image) {
                $upload = new Photo($image);
                $modelImage = new TourImage;
                $modelImage->origin = str_replace('public/', '', $upload->uploadTo('tours'));
                $modelImage->thumb = str_replace('public/', '', $upload->resizeTo(400));
                $model->images_rel()->save($modelImage);
            }
        }

        if (isset($data['delete_images'])) {
            $delete_images = explode(',', $data['delete_images']);
            foreach ($delete_images as $image_id) {
                $modelImage = TourImage::find($image_id);
                if ($modelImage) {
                    $modelImage->deleteImageOnStorage();
                    $modelImage->delete();
                }
            }
        }

        if (isset($data['banners'])) {
            foreach ($data['banners'] as $banner) {
                $upload = new Photo($banner);
                $modelBanner = new TourBanner;
                $modelBanner->origin = str_replace('public/', '', $upload->uploadTo('tours/banners'));
                $model->banners_rel()->save($modelBanner);
            }
        }

        if (isset($data['delete_banners'])) {
            $delete_banners = explode(',', $data['delete_banners']);
            foreach ($delete_banners as $banner_id) {
                $modelBanner = TourBanner::find($banner_id);
                if ($modelBanner) {
                    $modelBanner->deleteImageOnStorage();
                    $modelBanner->delete();
                }
            }
        }

        $model->save();
    }

    public function createOrUpdateSchedule($data, $id = null)
    {
        if ($id) {
            $model = TourSchedule::find($id);
        } else {
            $model = new TourSchedule;
        }

        $model->tour_id = $data['tour_id'];
        $model->title = $data['title'];
        $model->short_title = $data['short_title'];
        $model->content = $data['content'];

        $model->save();

        if (isset($data['images'])) {
            foreach ($data['images'] as $image) {
                $upload = new Photo($image);
                $modelImage = new TourScheduleImage;
                $modelImage->origin = str_replace('public/', '', $upload->uploadTo('tours'));
                $modelImage->thumb = str_replace('public/', '', $upload->resizeTo(500));
                $model->images()->save($modelImage);
            }
        }

        $model->save();
    }

    public function getPlaces($request)
    {
        $formatted_datas = [];
        $term = trim($request->q);

        $places = Place::where('title', 'LIKE', '%' . $term . '%')->get();
        foreach ($places as $data) {
            $formatted_datas[] = ['id' => $data->id, 'text' => $data->title];
        }

        return $formatted_datas;
    }

    public function getTours($request)
    {
        $formatted_datas = [];
        $term = trim($request->q);

        $tours = Tour::where('title', 'LIKE', '%' . $term . '%')->get();
        foreach ($tours as $data) {
            $formatted_datas[] = ['id' => $data->id, 'text' => $data->title];
        }

        return $formatted_datas;
    }

    public function getTourDetails($request)
    {
        $formatted_datas = [];
        $term = trim($request->q);

        $tour_details = TourDetail::where('start_date', 'LIKE', '%' . $term . '%')
            ->where('tour_id', trim($request->tour_id))
            ->get();

        foreach ($tour_details as $data) {
            $formatted_datas[] = ['id' => $data->id, 'text' => $data->start_date];
        }

        return $formatted_datas;
    }

    public function dataTable($request)
    {
        $tours = Tour::select(['tours.id', 'tours.title', 'tours.type', 'tours.from_place', 'tours.to_place', 'tours.time_range', 'tours.is_hot', 'tours.is_coming']);

        $dataTable = Datatables::eloquent($tours)
            ->filter(function ($query) use ($request) {
                if (trim($request->get('keyword')) !== "") {
                    $query->where(function ($sub) use ($request) {
                        $sub->where('tours.name', 'like', '%' . $request->get('keyword') . '%');
                    });
                }
            }, true)
            ->addColumn('is_coming', function ($place) {
                $html = '';

                if ($place->is_coming == ACTIVE) {
                    $html = '<h5><span class="badge badge-primary">' . COMING_TEXT[$place->is_coming] . '</span></h5>';
                } else {
                    $html = '<h5><span class="badge badge-secondary">' . COMING_TEXT[$place->is_coming] . '</span></h5>';
                }

                return $html;
            })
            ->addColumn('is_hot', function ($place) {
                $html = '';

                if ($place->is_hot == ACTIVE) {
                    $html = '<h5><span class="badge badge-primary">' . HOT_TEXT[$place->is_hot] . '</span></h5>';
                } else {
                    $html = '<h5><span class="badge badge-secondary">' . HOT_TEXT[$place->is_hot] . '</span></h5>';
                }

                return $html;
            })
            ->addColumn('type', function ($place) {
                $html = '';

                if ($place->type == INTERNATIONAL) {
                    $html = '<h5><span class="badge badge-primary">' . TOUR_TYPE_TEXT[$place->type] . '</span></h5>';
                } else {
                    $html = '<h5><span class="badge badge-secondary">' . TOUR_TYPE_TEXT[$place->type] . '</span></h5>';
                }

                return $html;
            })
            ->addColumn('action', function ($tour) {
                $html = '';
                $html .= '<a href="' . route('admin.tours.details.add', ['tour' => $tour->id]) . '" class="btn btn-sm btn-primary mb-1" style="margin-right: 5px"><i class="glyphicon glyphicon-add"></i> Add Detail</a>';
                $html .= '<a href="' . route('admin.tours.schedules.add', ['tour' => $tour->id]) . '" class="btn btn-sm btn-primary mb-1" style="margin-right: 5px"><i class="glyphicon glyphicon-add"></i> Add Schedule</a>';
                $html .= '<a href="' . route('admin.tours.edit', ['tour' => $tour->id]) . '" class="btn btn-sm btn-primary mb-1" style="margin-right: 5px"><i class="glyphicon glyphicon-edit"></i> Sửa</a>';
                $html .= '<a href="#" class="bt-delete btn btn-sm btn-danger mb-1" data-id="' . $tour->id . '" data-name="' . $tour->title . '">';
                $html .= '<i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a>';
                return $html;
            })
            ->rawColumns(['action', 'is_hot', 'is_coming', 'type'])
            ->toJson();

        return $dataTable;
    }

    public function dataTableSchedules($tour_id, $request)
    {
        $tour_schedules = TourSchedule::select(['tour_schedules.id', 'tour_schedules.title', 'tour_schedules.short_title'])
            ->where('tour_schedules.tour_id', $tour_id);

        $dataTable = Datatables::eloquent($tour_schedules)
            ->filter(function ($query) use ($request) {
                if (trim($request->get('keyword')) !== "") {
                    $query->where(function ($sub) use ($request) {
                        $sub->where('tour_schedules.name', 'like', '%' . $request->get('keyword') . '%');
                    });
                }
            }, true)
            ->addColumn('action', function ($schedule) use ($tour_id) {
                $html = '';
                $html .= '<a href="' . route('admin.tours.schedules.edit', ['tour' => $tour_id, 'schedule' => $schedule->id]) . '" class="btn btn-xs btn-primary" style="margin-right: 5px"><i class="glyphicon glyphicon-edit"></i> Sửa</a>';
                $html .= '<a href="#" class="bt-delete btn btn-xs btn-danger" data-id="' . $schedule->id . '" data-name="' . $schedule->title . '">';
                $html .= '<i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a>';
                return $html;
            })
            ->rawColumns(['action'])
            ->toJson();

        return $dataTable;
    }

    public function createOrUpdateDetail($data, $id = null)
    {
        if ($id) {
            $model = TourDetail::find($id);
        } else {
            $model = new TourDetail;
        }

        $model->tour_id = $data['tour_id'];
        $model->start_date = $data['start_date'];
        $model->flight_in = $data['flight_in'];
        $model->flight_out = $data['flight_out'];
        $model->price = $data['price'];
        $model->kid_price = $data['kid_price'];
        $model->baby_price = $data['baby_price'];
        $model->single_room_price = $data['single_room_price'];
        $model->index = $data['index'];
        $model->active = $data['active'];

        $model->save();

        $model->code = $model->tour_id . '-' . $this->convert_flight_to_code($model->flight_in) . '-' . str_replace('/', '', $model->start_date);
        $model->save();
    }

    public function convert_flight_to_code($str)
    {
        $arr = explode(" ", $str);
        return $arr[0];
    }

    public function dataTableDetails($tour_id, $request)
    {
        $tour_details = TourDetail::select([
            'tour_details.id', 'tour_details.start_date',
            'tour_details.flight_in', 'tour_details.flight_out', 'tour_details.active'
        ])
            ->where('tour_details.tour_id', $tour_id);

        $dataTable = Datatables::eloquent($tour_details)
            ->filter(function ($query) use ($request) {
                if (trim($request->get('keyword')) !== "") {
                    $query->where(function ($sub) use ($request) {
                        $sub->where('tour_details.name', 'like', '%' . $request->get('keyword') . '%');
                    });
                }
            }, true)
            ->addColumn('action', function ($detail) use ($tour_id) {
                $html = '';
                $html .= '<a href="' . route('admin.tours.details.edit', ['tour' => $tour_id, 'detail' => $detail->id]) . '" class="btn btn-xs btn-primary" style="margin-right: 5px"><i class="glyphicon glyphicon-edit"></i> Sửa</a>';
                $html .= '<a href="#" class="bt-delete btn btn-xs btn-danger" data-id="' . $detail->id . '" data-name="' . $detail->code . '">';
                $html .= '<i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a>';
                return $html;
            })
            ->rawColumns(['action'])
            ->toJson();

        return $dataTable;
    }
}
