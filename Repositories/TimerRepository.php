<?php


namespace Modules\TimerCRM\Repositories;


use Modules\BaseCore\Actions\Dates\ComputedDiffDateInSeconds;
use Modules\BaseCore\Repositories\AbstractRepository;
use Modules\CoreCRM\Models\Commercial;
use Modules\TimerCRM\Models\Timer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\TimerCRM\Contracts\Repositories\TimerRepositoryContract;

class TimerRepository extends AbstractRepository implements TimerRepositoryContract
{

    public function getModel(): Model
    {
        return new Timer();
    }

    public function searchQuery(Builder $query, string $value, mixed $parent = null): Builder
    {
        return $query->whereDate('start', $value);
    }

    public function start(Commercial $commercial): Timer
    {
        $timer = new Timer();
        $timer->commercial()->associate($commercial);
        $timer->start = Carbon::now();
        $timer->save();

        return $timer;
    }

    public function stop(Timer $timer): Timer
    {
        $timer->count = $this->getTimeInSeconds($timer);
        $timer->save();

        return $timer;
    }

    public function getTimeInSeconds(Timer $timer): int
    {
        $count =  (new ComputedDiffDateInSeconds())->getDates($timer->start->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s'));

        return $count['duration'];
    }


    public function fetchTimerStarted(Commercial $commercial): ?Timer
    {
        return Timer::whereHas('commercial', function ($query) use ($commercial){
            $query->where('id', $commercial->id);
        })->whereNull('count')->first();
    }


    public function getTimeByPeriode(Commercial $commercial, Carbon $dateStart, Carbon $dateEnd): ?Collection
    {
        return Timer::whereHas('commercial', function ($query) use ($commercial){
        $query->where('id', $commercial->id);
    })->whereBetween('start', [$dateStart, $dateEnd])->orderBy('id', 'desc')->limit(5)->get();
    }

    public function fetchTimerStartedInSinceWhenTime(Commercial $commercial): int
    {
        $timer = $this->fetchTimerStarted($commercial);
        if(!is_null($timer))
        {
            return $timer->start->diffInSeconds(Carbon::now());
        } else {
            return 0;
        }

    }
}
