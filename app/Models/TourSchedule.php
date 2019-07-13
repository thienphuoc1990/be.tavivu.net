<?php

namespace App\Models;

class TourSchedule extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tour_id',
        'short_title',
        'title',
        'content'
    ];

    /**
     * A tour can have many images.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images() {
        return $this->hasMany('App\Models\TourScheduleImage');
    }
}
