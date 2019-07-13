<?php

namespace App\Models;

class TourOrder extends BaseModel
{
    /**
     * Get the post that owns the comment.
     */
    public function tour()
    {
        return $this->belongsTo('App\Models\Tour');
    }
    /**
     * Get the post that owns the comment.
     */
    public function tour_detail()
    {
        return $this->belongsTo('App\Models\TourDetail');
    }
}
