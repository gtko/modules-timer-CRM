<?php


namespace Modules\TimerCRM\Contracts\Repositories;


use Modules\BaseCore\Interfaces\RepositoryFetchable;
use Modules\CoreCRM\Models\Commercial;
use Modules\TimerCRM\Models\Timer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface TimerRepositoryContract extends RepositoryFetchable
{

    public function start(Commercial $commmercial): Timer;

    public function stop(Timer $timer): Timer;

    public function add(Commercial $commercial, Carbon $dateStart, Carbon $dateEnd): Timer;

    public function getTimeInSeconds(Timer $timer): int;

    public function fetchTimerStarted(Commercial $commercial): ?Timer;

    public function fetchTimerStartedInSinceWhenTime(Commercial $commercial): int;

    public function getTimeByPeriode(Commercial $commercial, Carbon $dateStart = null, Carbon $dateEnd = null): ?Collection;

    public function getTotalTimeByCommercialPeriode(Commercial $commercial, Carbon|null $debut, Carbon|null $fin): string;

    public function getTotalDaysPresentielByCommercialPeriode(Commercial $commercial, Carbon|null $debut, Carbon|null $fin): string;

    public function modifTime(Timer $timer, Carbon $start, Carbon $end): Timer;

    public function delete(Timer $timer): bool;

}
