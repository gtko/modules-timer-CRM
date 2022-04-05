<?php


namespace Modules\TimerCRM\Repositories;


use Carbon\CarbonInterval;
use Modules\BaseCore\Actions\Dates\ComputedDiffDateInSeconds;
use Modules\BaseCore\Actions\Dates\DateStringToCarbon;
use Modules\BaseCore\Repositories\AbstractRepository;
use Modules\CoreCRM\Models\Commercial;
use Modules\TimerCRM\Contracts\Repositories\Commercialv;
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
        $count = (new ComputedDiffDateInSeconds())->getDates($timer->start->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s'));

        return $count['duration'];
    }


    public function fetchTimerStarted(Commercial $commercial): ?Timer
    {
        return Timer::whereHas('commercial', function ($query) use ($commercial) {
            $query->where('id', $commercial->id);
        })->whereNull('count')->first();
    }


    public function getTimeByPeriode(Commercial $commercial, Carbon $dateStart = null, Carbon $dateEnd = null): ?Collection
    {
//        dd($dateStart, $dateEnd);
        $query = Timer::whereHas('commercial', function ($query) use ($commercial) {
            $query->where('id', $commercial->id);
        });

        if ($dateStart && $dateEnd) {
            $query->whereBetween('start', [$dateStart, $dateEnd]);
//
        }

        return $query->orderBy('id', 'desc')->get();
    }

    public function fetchTimerStartedInSinceWhenTime(Commercial $commercial): int
    {
        $timer = $this->fetchTimerStarted($commercial);
        if (!is_null($timer)) {
            return $timer->start->diffInSeconds(Carbon::now());
        } else {
            return 0;
        }

    }

    public function getTotalTimeByCommercialPeriode(Commercial $commercial, ?Carbon $debut, ?Carbon $fin): string
    {

        if ($debut !== null && $fin !== null) {
            $times = $this->getTimeByPeriode($commercial, $debut, $fin);

        } else {
            $times = $this->getTimeByPeriode($commercial, Carbon::Now()->subYears(20), Carbon::now());
        }

        $timesCount = 0;
        foreach ($times as $time) {
            $timesCount = $timesCount + $time->count;
        }

        if($timesCount > 0) {

            $dt = Carbon::now();
            $hours = $dt->diffInHours($dt->copy()->addSeconds($timesCount));
            $minutes = $dt->diffInMinutes($dt->copy()->addSeconds($timesCount)->subHours($hours));

            return CarbonInterval::hours($hours)->minutes($minutes)->forHumans();
        }

        return 'Aucun temps';
    }

    public function modifTime(Timer $timer, Carbon $start, Carbon $end): Timer
    {


        $newCount = $start->diffInSeconds($end);

        $timer->start = $start;
        $timer->count = $newCount;
        $timer->save();

        return $timer;
    }

    public function add(Commercial $commercial, Carbon $dateStart, Carbon $dateEnd): Timer
    {
        $timer = new Timer();
        $timer->start = $dateStart;
        $timer->count = $dateStart->diffInSeconds($dateEnd);
        $timer->commercial_id = $commercial->id;
        $timer->save();

        return $timer;
    }

    public function delete(Timer $timer): bool
    {
        return $timer->delete();
    }
}
