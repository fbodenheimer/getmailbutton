function getmailactioncallback(response) {
	rcmail.display_message(response.message);
}

rcmail.addEventListener('init', function(evt) {
  // Handler for button-clik
  rcmail.register_command('plugin.getmailaction', function() {
	  rcmail.http_request('plugin.getmailaction', {}, rcmail.display_message(rcmail.gettext('running', 'getmailbutton')));
  }, true);
  // Listener for feedback after getmail completed
  rcmail.addEventListener('plugin.getmailactioncallback', getmailactioncallback);

});
