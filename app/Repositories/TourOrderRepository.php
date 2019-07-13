<?php

namespace App\Repositories;

use App\Models\TourOrder;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use Response;

use App\Repositories\BaseRepository;

Class TourOrderRepository extends BaseRepository
{
    public function dataTable($request)
    {
        $tour_orders = TourOrder::select(['tour_orders.id', 'tour_orders.name', 'tour_orders.email', 'tour_orders.phone', 'tour_orders.address', 'tour_orders.tour_id', 'tour_orders.tour_detail_id', 'tour_orders.tickets', 'tour_orders.kid_tickets', 'tour_orders.baby_tickets', 'tour_orders.status']);

        $dataTable = Datatables::eloquent($tour_orders)
        ->filter(function ($query) use ($request) {
            if (trim($request->get('keyword')) !== "") {
                $query->where(function ($sub) use ($request) {
                    $sub->where('tour_orders.name', 'like', '%' . $request->get('keyword') . '%');
                });
            }
        }, true)
        ->addColumn('action', function ($tour_order) {
            $html = '';
            $html .= '<a href="' . route('admin.tour_orders.edit', ['tour_order' => $tour_order->id]) . '" class="btn btn-xs btn-primary" style="margin-right: 5px"><i class="glyphicon glyphicon-edit"></i> Sửa</a>';
            $html .= '<a href="#" class="bt-delete btn btn-xs btn-danger" data-id="' . $tour_order->id . '" data-name="' . $tour_order->title . '">';
            $html .= '<i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a>';
            return $html;
        })
        ->addColumn('status', function ($tour_order) {
            $html = '';

            if ($tour_order->status == ACCEPTED) {
                $html = '<h5><span class="badge badge-primary">' . TOUR_ORDER_STATUS_TEXT[$tour_order->status] . '</span></h5>';
            }else{
                $html = '<h5><span class="badge badge-secondary">' . TOUR_ORDER_STATUS_TEXT[$tour_order->status] . '</span></h5>';
            }

            return $html;
        })
        ->addColumn('tour', function($tour_order) {
            $html = '';

            if($tour_order->tour) {
                $html = $tour_order->tour->title;
            }

            return $html;
        })
        ->addColumn('start_date', function($tour_order) {
            $html = '';

            if($tour_order->tour_detail) {
                $html = $tour_order->tour_detail->start_date;
            }

            return $html;
        })
        ->addColumn('customer_info', function($tour_order) {
            $html = '';

            $html .= '<p>'.$tour_order->name.'</p>';
            $html .= '<p>'.$tour_order->phone.'</p>';
            $html .= '<p>'.$tour_order->email.'</p>';
            $html .= '<p>'.$tour_order->address.'</p>';

            return $html;
        })
        ->addColumn('tickets', function($tour_order) {
            $html = '';

            $html .= '<p>Tickets: '.$tour_order->tickets.'</p>';
            $html .= '<p>Kid tickets: '.$tour_order->kid_tickets.'</p>';
            $html .= '<p>Baby tickets: '.$tour_order->baby_tickets.'</p>';

            return $html;
        })
        ->rawColumns(['action', 'status', 'tour', 'start_date', 'customer_info', 'tickets'])
        ->toJson();

        return $dataTable;
    }

    public function createOrUpdate($data, $id = null) {
        if ($id) {
            $model = TourOrder::find($id);
        } else {
            $model = new TourOrder;
        }

        $model->tour_id = $data['tour_id'];
        $model->tour_detail_id = $data['tour_detail_id'];
        $model->tickets = $data['tickets'];
        $model->kid_tickets = $data['kid_tickets'];
        $model->baby_tickets = $data['baby_tickets'];
        $model->name = $data['name'];
        $model->phone = $data['phone'];
        $model->email = $data['email'];
        $model->address = $data['address'];
        $model->notes = $data['notes'];
        $model->status = $data['status'];

        $model->save();
    }

    public function getTourOrder($id) {
        $tour_order = TourOrder::find($id);
        $tour_order->tour;
        $tour_order->tour_detail;
        return $tour_order;
    }
}
