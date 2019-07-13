<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Repositories\API\ContactRepository;

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
    }

    public function sendContact(ContactRepository $contact)
    {
        $result = [
            "code" => "SEND_SU",
            "message" => "Send contact successfull"
        ];

        $contact->sendContact($this->request);

        return response()->json($result, 200);
    }
}
