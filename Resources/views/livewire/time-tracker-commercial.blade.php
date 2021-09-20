<div class="text-white w-full">

    <div class="my-4 shadow-2xl">
        <div class="flex flex-row justify-between px-4 py-2 bg-blue-400 rounded-t-lg ">

             <span class="flex flex-col items-center">
                <span id="hours" class="text-3xl">00</span>
                <span>Heure</span>
             </span>

            <span class="text-2xl">:</span>

            <span class="flex flex-col items-center">
                <span id="minutes" class="text-3xl">00</span>
                <span>Minute</span>
            </span>

            <span class="text-2xl">:</span>

            <span class="flex flex-col items-center self-end">
                <span id="seconds" class="text-3xl">00</span>
                <span>Seconde</span>
            </span>

        </div>


        <div class="bg-black bg-opacity-50 py-2 px-1 rounded-b-lg text-center">

            @if (!$actif)
                <span class="cursor-pointer btn btn-sm btn-success" wire:click="start()" @click="start()">
                Play
        </span>
            @else
                <span class="flex flex-row justify-center space-x-2">
                    <span class="cursor-pointer btn btn-sm btn-danger" wire:click="stop()" @click="stop()">
                        Stop
                    </span>
                    <span class="cursor-pointer btn btn-sm btn-warning" wire:click="stop()" @click="stop()">
                        Pause
                    </span>
                </span>
            @endif
        </div>
{{--        <div>--}}
{{--            <div class="row my-3">--}}
{{--                <div class="col-6">--}}
{{--                    <h2 class="lblTime" id="timer">00:00:00</h2>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="row my-3">--}}
{{--                <div class="col-5">--}}

{{--                    <button type="button" class="btn btn-views" onclick="timeCount()"> <i class="fas fa-play"></i>--}}
{{--                        Start--}}
{{--                    </button>--}}

{{--                    <button type="button" class="btn btn-views" onclick="stopCount()">--}}
{{--                        <i class="fas fa-pause"></i>--}}
{{--                        Pause--}}
{{--                    </button>--}}
{{--                    <button type="button" class="btn btn-views" onclick="resetCount()"><i class="fas fa-undo"></i> Reset</button>--}}
{{--                </div>--}}

{{--            </div>--}}

{{--            <div class="row my-3 align-items-end">--}}
{{--                <div class="col-2">--}}

{{--                    <label class="lblTime" for="timeStart">Start</label>--}}
{{--                    <input id="timeStart" type="text" value="00:00:00" placeholder="00:00:00" class="form-control">--}}
{{--                </div>--}}
{{--                <div class="col-2">--}}
{{--                    <label class="lblTime" for="timeEnd">Stop</label>--}}
{{--                    <input id="timeEnd" type="text" value="23:59:59" placeholder="00:00:00" class="form-control">--}}
{{--                </div>--}}

{{--                <div class="col-2">--}}
{{--                    <button type="button" class="btn btn-views" onclick="setTime()">Set</button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>


    @push('scripts')
        <script>

            let startTime = 0;
            let endTime = 0;
            let currentTime = 0;
            let timeDiff = 0;
            let timer = null;

            setTime = () => {
                startTime = document.getElementById("timeStart").value;
                document.getElementById("timer").innerHTML = startTime;
            }

            timeCount = () => {
                currentTime = document.getElementById("timer").innerHTML;

                if (currentTime != "00:00:00") {
                    startTime = moment(document.getElementById("timer").innerHTML, "hh:mm:ss");
                    console.log(startTime);
                } else {
                    startTime = moment(document.getElementById("timeStart").value, "hh:mm:ss");
                }

                endTime = moment(document.getElementById("timeEnd").value, "hh:mm:ss");

                timeDiff = endTime.diff(startTime);

                timer = setInterval(countDown, 1000);
            }

            countDown = () => {
                startTime.add(1, "second");

                document.getElementById("timer").innerHTML = startTime.format("HH:mm:ss");

                timeDiff = endTime.diff(startTime);
                if (timeDiff == 0) {
                    stopCount();
                }
            }

            stopCount = () =>{
                clearInterval(timer);
            }

            resetCount = () => {
                clearInterval(timer);
                document.getElementById("timer").innerHTML = "00:00:00";
            }









            {{--var myTimer;--}}

            {{--if ({{$countTimer}} > 0) {--}}
            {{--    start()--}}
            {{--}--}}

            {{--function start() {--}}

            {{--    totalSeconds = {{$countTimer}};--}}
            {{--    myTimer = setInterval(myClock, 1000);--}}

            {{--    function myClock() {--}}

            {{--        var c = {{$countTimer}};--}}
            {{--        hours = Math.floor(totalSeconds / 3600);--}}

            {{--        totalSeconds %= 3600;--}}
            {{--        minutes = Math.floor(totalSeconds / 60);--}}
            {{--        seconds = totalSeconds % 60;--}}
            {{--        document.getElementById("seconds").innerHTML = seconds;--}}
            {{--        document.getElementById("minutes").innerHTML = minutes;--}}
            {{--        document.getElementById("hours").innerHTML = hours;--}}
            {{--        totalSeconds++--}}
            {{--    }--}}

            {{--}--}}

            {{--function stop() {--}}
            {{--    clearInterval(myTimer)--}}


            {{--}--}}
        </script>
    @endpush()
</div>
