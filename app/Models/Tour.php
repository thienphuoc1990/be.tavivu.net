<?php

namespace App\Models;

class Tour extends BaseModel
{
    /**
     * A tour can have many images.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images_rel() {
        return $this->hasMany('App\Models\TourImage');
    }
    /**
     * A tour can have many images.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function banners_rel() {
        return $this->hasMany('App\Models\TourBanner');
    }

    /**
     * A tour can have many schedules.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedules() {
        return $this->hasMany('App\Models\TourSchedule');
    }

    /**
     * A tour can have many schedules.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details() {
        return $this->hasMany('App\Models\TourDetail');
    }

    /**
     * A tour has one form place
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fromPlace() {
        $data = [];

        $arr = explode(',', str_replace('-', '', $this->from_place));
        foreach($arr as $item) {
            $data[] = Place::find($item)->title;
        }

        return implode(', ', $data);
    }

    /**
     * A tour has one form place
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function toPlace() {
        $data = [];

        $arr = explode(',', str_replace('-', '', $this->to_place));
        foreach($arr as $item) {
            $data[] = Place::find($item)->title;
        }

        return implode(', ', $data);
    }

    public function images() {
        $data = [];

        if (isset($this->images_rel)) {
            foreach($this->images_rel as $item) {
                $data[] = asset('storage/' .$item->origin);
            }
        }

        return $data;
    }

    public function generateData() {
        $this->image();
        $this->link_to();
        $this->min_start_date_and_price();
        $this->toPlace = $this->toPlace();
        $this->fromPlace = $this->fromPlace();
    }

    public function image() {
        if (count($this->images_rel) > 0) {
                $this->image = asset('storage/' .$this->images_rel[0]->origin);
        }
    }

    public function link_to() {
        $this->linkto = 'tours/' . $this->id;
    }

    public function min_start_date_and_price() {
        if (isset($this->details) && count($this->details) > 0) {
            $this->startDate = $this->details[0]->start_date;
            $this->price = format_price($this->details[0]->price);
        }
    }

}
