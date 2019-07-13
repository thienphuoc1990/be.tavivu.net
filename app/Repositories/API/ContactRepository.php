<?php

namespace App\Repositories\API;

use Illuminate\Support\Facades\Cache;
use Response;
use Mail;

use App\Models\Contact;
use App\Repositories\API\BaseRepository;
use App\Mail\SendContact;

class ContactRepository extends BaseRepository
{
    public function sendContact($request)
    {
        $input = $request->all()
        ;
        $model = new Contact;

        $model->name = $input['name'];
        $model->phone = $input['phone'];
        $model->email = $input['email'];
        $model->address = $input['address'];
        $model->content = $input['content'];

        $model->save();

        $contact = [
            "name" => $input['name'],
            "phone" => $input['phone'],
            "email" => $input['email'],
            "address" => $input['address'],
            "content" => $input['content'],
        ];

        Mail::to('info@tavivu.net')->send(new SendContact($contact));

        return 'Email was sent';
    }
}
