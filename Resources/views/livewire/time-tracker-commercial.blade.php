<div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
      function tiimer(){
                return {
                    actif: @entangle('actif'),
                    timerServer : @entangle('countTimer'),
                    startTime : null,
                    timer : null,
                    hours: '00',
                    minutes : '00',
                    seconds :'00',
                    init(){
                      if(this.actif){
                          this.timeCount();
                      }
                    },
                    timeCount(){
                        if(this.timerServer > 0) {
                            let timeStr = moment.utc(this.timerServer*1000).format('HH:mm:ss');
                            this.startTime = moment(timeStr, 'HH:mm:ss');
                        }else{
                            this.startTime = moment('00:00:00', 'HH:mm:ss');
                        }
                        this.timer = setInterval(() => this.countDown(), 1000);
                    },
                    countDown(){
                        this.startTime.add(1, "second");
                        this.hours = this.startTime.format("HH");
                        this.minutes = this.startTime.format("mm");
                        this.seconds = this.startTime.format("ss");
                    },
                    stopCount(){
                        clearInterval(this.timer);
                    },
                    resetCount(){
                        clearInterval(this.timer);
                        this.hours = '00';
                        this.minutes = '00';
                        this.seconds = '00';
                    },
                    start(){
                        @this.start()
                        this.timeCount()
                    },
                    stop(){
                        @this.stop()
                        this.stopCount()
                        this.resetCount()
                    },
                }
            }
    </script>
    <div class="text-white w-full" x-data="tiimer">
        <div class="my-4 shadow-2xl">
            <div class="flex flex-row justify-between px-4 py-2 bg-blue-400 rounded-t-lg ">

                 <span class="flex flex-col items-center">
                    <span id="hours" class="text-3xl" x-text="hours">00</span>
                    <span>Heure</span>
                 </span>

                <span class="text-2xl">:</span>

                <span class="flex flex-col items-center">
                    <span id="minutes" class="text-3xl" x-text="minutes">00</span>
                    <span>Minute</span>
                </span>

                <span class="text-2xl">:</span>

                <span class="flex flex-col items-center self-end">
                    <span id="seconds" class="text-3xl" x-text="seconds">00</span>
                    <span>Seconde</span>
                </span>
            </div>
            <div class="bg-black bg-opacity-50 py-2 px-1 rounded-b-lg text-center">
                    <span x-show='!actif' class="cursor-pointer btn btn-sm btn-success" x-on:click="start()">
                        Play
                    </span>
                    <span x-show='actif' class="flex flex-row justify-center space-x-2">
                        <span class="cursor-pointer btn btn-sm btn-danger" x-on:click="stop()">
                            Stop
                        </span>
                    </span>
            </div>
        </div>
    </div>
</div>
