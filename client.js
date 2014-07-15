var getmailbuttontimer = null;


function initTimer(seconds) {
	getmailbuttontimer = {
			isTimerRunning: false,
			timerSeconds: seconds,
			timer: null,
			startTimer: function() {
				if(!this.isTimerRunning && this.timerSeconds > 0) {
					this.isTimerRunning = true;
					var that = this;
					this.timer = setTimeout(function() {
						that.isTimerRunning = false;
						rcmail.http_request('plugin.getmailaction', {}, rcmail.display_message(rcmail.gettext('running', 'getmailbutton')));
					
					}, seconds * 1000);
				}        
			},
			stopTimer: function() {
				this.isTimerRunning = false;
				clearInterval(this.timer);
			}
		}
}

function getmailsettimer(response) {
	var seconds = response.timer;
	if(getmailbuttontimer == null) {
		if(seconds > 0)
		{
			initTimer(seconds);
			getmailbuttontimer.startTimer();
		}
	}else{
		getmailbuttontimer.startTimer();
	}

}



function getmailactioncallback(response) {
	rcmail.display_message(response.message);
	if(getmailbuttontimer != null)
		getmailbuttontimer.startTimer();
}

rcmail.addEventListener('init', function(evt) {
	// Handler for button-click
	rcmail.register_command('plugin.getmailaction', function() {
		if(getmailbuttontimer != null)
			getmailbuttontimer.stopTimer();
		rcmail.http_request('plugin.getmailaction', {}, rcmail.display_message(rcmail.gettext('running', 'getmailbutton')));
	}, true);
	// Listener for feedback after getmail completed
	rcmail.addEventListener('plugin.getmailactioncallback', getmailactioncallback);
	// Listener for set timer
	rcmail.addEventListener('plugin.getmailsettimer', getmailsettimer);
});
