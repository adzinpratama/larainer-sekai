<?php

namespace App\Traits\Model;

use Carbon\Carbon;

trait SupportAttribute
{

    public function getTimeCreatedAttribute()
    {
        return Carbon::parse($this->created_at)
            ->locale('id')
            ->diffForHumans();
    }

    public function getTimeCreatedCommonAttribute()
    {
        return Carbon::parse($this->created_at)
            ->locale('id')
            ->format('d-m-Y H:i:s');
    }

    public function getTimeCreatedIdAttribute()
    {
        return Carbon::parse($this->created_at)
            ->isoFormat('dddd, D MMMM Y, HH:mm');
    }
}
