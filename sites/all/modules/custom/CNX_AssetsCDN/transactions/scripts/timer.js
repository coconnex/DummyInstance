let bTimer = null;
const bookingTimer = class{
  remainingTime = {'days':0,'hours':0,'mins':0,'secs':0};
  remainingSecs;
  dayElem;
  hourElem;
  minElem;
  secElem;
  intervalMiliSecs = 1000;
  intervalObj;
  intervalCount = 0;

  constructor(remainingTime, intervalMiliSecs = null){
    if(remainingTime){
      let arr_time_parts = remainingTime.split(':');

      if(arr_time_parts.length === 4){
        this.remainingTime.days = parseInt(arr_time_parts[0]);
        this.remainingTime.hours = parseInt(arr_time_parts[1]);
        this.remainingTime.mins = parseInt(arr_time_parts[2]);
        this.remainingTime.secs = parseInt(arr_time_parts[3]);
      }
    }
    if(intervalMiliSecs){
      this.intervalMiliSecs = intervalMiliSecs;
    }
    this.setRemainingSecs();
    this.setElems();
    this.displayRemainingTime();
  }

  setElems(){
    this.dayElem = document.querySelector("#bookingtimer #numdays");
    this.hourElem = document.querySelector("#bookingtimer #numhrs");
    this.minElem = document.querySelector("#bookingtimer #nummin");
    this.secElem = document.querySelector('#bookingtimer #numsec');
  }

  setRemainingSecs(){
    this.remainingSecs = (this.remainingTime.days * 24 * 60);
    // console.log(this.remainingSecs);
    this.remainingSecs += (this.remainingTime.hours * 60 * 60);
    // console.log(this.remainingSecs);
    this.remainingSecs += (this.remainingTime.mins * 60);
    // console.log(this.remainingSecs);
    this.remainingSecs += this.remainingTime.secs ;
    // console.log(this.remainingSecs);
  }

  startTimer() {
    this.intervalObj = setInterval(this.intervalHandler, this.intervalMiliSecs);
  }

  intervalHandler(){
    if (bTimer.remainingTime.days <= 0 && bTimer.remainingTime.hours <= 0 && bTimer.remainingTime.mins <= 0 && bTimer.remainingTime.secs <= 0) {
      clearInterval(bTimer.intervalObj);
      // window.location.replace(window.location.href);
      location.reload();
    } else {
      bTimer.intervalCount += 1;
      bTimer.updateRemainingTime();
    }
  }

  updateRemainingTime() {
    let elapsedSecs = this.intervalMiliSecs/1000;
    this.remainingSecs -= elapsedSecs;

    this.remainingTime.days = Math.floor(this.remainingSecs / (60 * 60 * 24));
    this.remainingTime.hours = Math.floor(this.remainingSecs % (60 * 60 * 24) / (60 * 60));
    this.remainingTime.mins = Math.floor((this.remainingSecs % (60 * 60)) / 60);
    this.remainingTime.secs = Math.floor((this.remainingSecs - (this.remainingTime.hours * 60 * 60) - (this.remainingTime.mins * 60)));

    this.displayRemainingTime()
  }

  displayRemainingTime(){
    if(this.dayElem){
      this.dayElem.innerHTML = this.remainingTime.days;
    }
    if(this.hourElem){
      this.hourElem.innerHTML = this.remainingTime.hours;
    }
    if(this.minElem){
      this.minElem.innerHTML = this.remainingTime.mins;
    }
    if(this.secElem){
      this.secElem.innerHTML = this.remainingTime.secs;
    }

  }
}

window.addEventListener('load',(event) => {

  let timerElem = document.querySelector('#remaining_booking_time');
  // console.log(timerElem);
  let remainingBookingTime = timerElem.value;
  if(timerElem){
    // console.log('Remaining Time = ' + remainingBookingTime);
    bTimer = new bookingTimer(remainingBookingTime);
    bTimer.startTimer();
  }
});