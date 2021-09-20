<?php

namespace Modules\TimerCRM\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\CoreCRM\Models\Commercial;

/**
 * @property int $commercial_id
 * @property Commercial $commercial
 * @property Carbon $start
 * @property int $count
 * @property int $id
 * @property Carbon $totalTime
 */
class Timer extends Model
{
    protected $dates = ["start"];

    public function commercial()
    {
        return $this->belongsTo(Commercial::class);
    }

    public function getTotalTimeAttribute()
    {
        $timeStart = $this->start;
        $seconde = $this->count;
        $count = $timeStart->diffForHumans($timeStart->copy()->addSeconds($seconde));

        return $count;

        }

}
