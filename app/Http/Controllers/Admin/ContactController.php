<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;

use App\Http\Controllers\Admin\BaseController;
use App\Repositories\ContactRepository;

class ContactController extends BaseController
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->_pushBreadCrumbs('Danh sách liên hệ', route('admin.contacts.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  App\Repositories\ContactRepository  $contact
     * @return \Illuminate\Http\Response
     */
    public function index(ContactRepository $contact)
    {
        if ($this->request->ajax()){
            return $contact->dataTable($this->request);
        }

        $this->data['title'] = 'Contact';
        return view('admin.contacts.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['title'] = 'Add new page';
        return view('admin.contacts.detail', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Repositories\ContactRepository  $contact
     * @return \Illuminate\Http\Response
     */
    public function store(ContactRepository $contact)
    {
        $input = $this->request->all();
        $id = $input['id'] ?? null;

        $message = 'Contact đã được tạo.';

        if ($id) {
            $message = 'Contact đã được cập nhật.';
        }

        $data = $contact->createOrUpdate($input, $id);

        return redirect()->route('admin.contacts.index')->withSuccess($message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Repositories\ContactRepository  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit($id, ContactRepository $contact)
    {
        $this->data['data'] = $contact->getContact($id);
        $this->data['title'] = 'Edit Contact';
        return view('admin.contacts.detail', $this->data);
    }

    public function delete(ContactRepository $contact)
    {
        // $ids = $this->request->get('ids');
        // $result = $contact->delete($ids);
        $result = ['haha'];

        return response()->json($result);
    }
}
