<?php

namespace App\Repositories;

use App\Models\Contact;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Response;

Class ContactRepository
{
    public function dataTable($request)
    {
        $contacts = Contact::select(['contacts.id', 'contacts.name', 'contacts.email', 'contacts.phone', 'contacts.address']);

        $dataTable = Datatables::eloquent($contacts)
        ->filter(function ($query) use ($request) {
            if (trim($request->get('keyword')) !== "") {
                $query->where(function ($sub) use ($request) {
                    $sub->where('contacts.name', 'like', '%' . $request->get('keyword') . '%');
                });
            }
        }, true)
        ->addColumn('action', function ($contact) {
            $html = '';
            $html .= '<a href="' . route('admin.contacts.edit', ['contact' => $contact->id]) . '" class="btn btn-xs btn-primary" style="margin-right: 5px"><i class="glyphicon glyphicon-edit"></i> Sửa</a>';
            $html .= '<a href="#" class="bt-delete btn btn-xs btn-danger" data-id="' . $contact->id . '" data-name="' . $contact->name . '">';
            $html .= '<i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a>';
            return $html;
        })
        ->rawColumns(['action'])
        ->toJson();

        return $dataTable;
    }

    public function createOrUpdate($data, $id = null) {
        if ($id) {
            $model = Contact::find($id);
        } else {
            $model = new Contact;
        }

        $model->name = $data['name'];
        $model->phone = $data['phone'];
        $model->email = $data['email'];
        $model->address = $data['address'];
        $model->content = $data['content'];

        $model->save();
    }

    public function getContact($id) {
        $contact = Contact::find($id);
        return $contact;
    }

	public function delete($ids)
	{
		$result = [
			'success' => true,
			'errors' => []
		];
		foreach ($ids as $id) {
			$model = Contact::find($id);
			if ($model === null) {
				$result['errors'][] = 'ID này ' . $id . ' không tồn tại';
				$result['success'] = false;
				continue;
			}
			if ($model->photo) {
                Storage::delete($model->photo);
            }
			$model->delete();
		}

		return $result;
	}
}
