<?php

namespace Modules\TimerCRM\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Modules\TimerCRM\Contracts\Repositories\TimerRepositoryContract;
use Modules\BaseCore\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;



class TimeTrackerCommercial extends Component
{

    public bool $actif = false;
    public int $timerId;
    public Collection $timeLastDay;
    public int $countTimer;


    public function mount(TimerRepositoryContract $repTimer)
    {
        $this->countTimer = $repTimer->fetchTimerStartedInSinceWhenTime(\Auth::commercial());



        $timer = $repTimer->fetchTimerStarted(\Auth::commercial());

        if ($timer) {
            $this->timerId = $timer->id;
            $this->actif = true;
        }
        $this->refresh($repTimer);
    }

    public function refresh (TimerRepositoryContract $repTimer)
    {
        $this->timeLastDay = $repTimer->getTimeByPeriode(\Auth::commercial(), Carbon::now()->addHours(2)->startOfDay(), Carbon::now()->addHours(2)->endOfDay());
    }

    public function start(TimerRepositoryContract $repTimer)
    {
        $commercial = \Auth::commercial();
        $timer = $repTimer->fetchTimerStarted($commercial);

        if (!$timer) {
            $timer = $repTimer->start($commercial);
        }

        $this->timerId = $timer->id;
        $this->actif = true;
    }

    public function stop(TimerRepositoryContract $repTimer)
    {
        $timer = $repTimer->fetchById($this->timerId);
        $repTimer->stop($timer);
        $this->actif = false;
        $this->refresh($repTimer);
    }

    public function render(TimerRepositoryContract $repTimer)
    {
        $commercial = \Auth::commercial();
        if($commercial) {
            return view('timercrm::livewire.time-tracker-commercial');
        }

        return '';
    }
}
