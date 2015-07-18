function changeTime() {
  var timeSplited = time.split(':');
  var hour = timeSplited[0];
  var minute = timeSplited[1];
  var second = timeSplited[2];
  second++;
  if(second==60) {
    second = '0';
    minute++;
    if(minute == 60){
      minute = '0';
      hour++;
    }
  }
	hour = '0'+hour;
	hour = hour.toString().substr(-2, 2);
	minute = '0'+minute;
	minute = minute.toString().substr(-2, 2);
	second = '0'+second;
	second = second.toString().substr(-2, 2);
  time = hour+':'+minute+':'+second;
  document.getElementById('time').innerHTML = time;
} 
var instance = self.setInterval(changeTime ,1000);