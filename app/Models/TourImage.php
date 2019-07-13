<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;

class TourImage extends BaseModel
{

    const THUMB = 150;
    const LARGE = 300;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tour_id',
        'origin',
        'large',
        'thumb'
    ];

    public function deleteImageOnStorage(){
        if ($this->origin) {
           Storage::delete($this->origin);
        }
        if ($this->large) {
           Storage::delete($this->large);
        }
        if ($this->thumb) {
           Storage::delete($this->thumb);
        }
    }
}
