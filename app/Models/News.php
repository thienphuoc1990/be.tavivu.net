<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    public function generateData() {
        $this->parseImage();
        $this->parseImageThumb();
        $this->link_to();
    }

    public function parseImage() {
        $this->image = asset('storage/' .$this->image);
    }

    public function parseImageThumb() {
        $this->thumb = asset('storage/' .$this->thumb);
    }

    public function link_to() {
        $this->linkto = 'bai-viet/' . $this->id;
    }
}
