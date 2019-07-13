<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    public function generateData() {
        $this->parseImage();
        $this->link_to();
    }

    public function parseImage() {
        $this->image = asset('storage/' .$this->image);
    }

    public function link_to() {
        if($this->type == IN_COUNTRY) {
            $this->linkto = 'tours-trong-nuoc?tp=' . $this->id;
        }else{
            $this->linkto = 'tours-quoc-te?tp=' . $this->id;
        }

    }
}
