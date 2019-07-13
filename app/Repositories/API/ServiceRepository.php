<?php

namespace App\Repositories\API;

use Illuminate\Support\Facades\Cache;
use Response;
use Mail;

use App\Models\Service;
use App\Repositories\API\BaseRepository;
use App\Mail\SendService;

class ServiceRepository extends BaseRepository
{
    public function sendService($request)
    {
        $input = $request->all()
        ;
        $model = new Service;

        $model->name = $input['name'];
        $model->phone = $input['phone'];
        $model->email = $input['email'];
        $model->type = $input['type'];
        $model->note = $input['note'];

        $model->save();

        $service = [
            "name" => $input['name'],
            "phone" => $input['phone'],
            "email" => $input['email'],
            "type" => $input['type'],
            "note" => $input['note'],
        ];

        Mail::to('info@tavivu.net')->send(new SendService($service));

        return 'Email was sent';
    }
}
