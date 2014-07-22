<?php
/**
 * getmail - Button
 *
 * This plugin adds a Button to the toolbar which allows to trigger the getmail-command
 *
 * @version 1.0
 * @author Florian Bodenheimer
 * @url https://github.com/fbodenheimer/getmailbutton
 */
class getmailbutton extends rcube_plugin
{
	private $listAdresses = array();
	private $username = "";
	function init()
	{
		$this->load_config();
		$rcmail = rcmail::get_instance();
		// Load configured Users
		$users = $rcmail->config->get("permitted_users", array());
		
		// Get current login
		$user = $_SESSION["username"];

		// Plugin is only shown and activated when current user is configured
		if(array_key_exists($user, $users))
		{
			$this->add_texts('localization/', true);
			$this->include_stylesheet($this->local_skin_path() .'/getmailbutton.css');
			$this->include_script('client.js');
			if($rcmail->task == "mail" && $rcmail->action == "") {		
				// Add button to toolbar
				$this->add_button(array(
							        'type' => 'link',
							        'label' => 'getmailbutton.buttontext',
							        'command' => 'plugin.getmailaction',
							        'class' => 'button getmail',
							        'classact' => 'button getmail',
							        'title' => 'getmailbutton.buttontitle'
								), 'toolbar');
			}	   
			// Register button-click action, calling refresh-method
			$this->register_action('plugin.getmailaction', array($this, 'refresh'));

			$this->listAdresses = $users[$user];
			$this->username = $user;
			$rcmail->output->command('plugin.getmailsettimer', array('timer' => intval($rcmail->config->get("getmail_ui_refreshtimer", 0))));
		}	

	
	}
	/**
	 * Action-Function called by the frontend when Button is pressed
	 * 
	 */
	function refresh($args) {
		$rcfiles = "";	
		if(is_array($this->listAdresses))
			foreach($this->listAdresses as $key => $value)
				$rcfiles.= " --rcfile \"".$value."\"";
		
		$rcmail = rcmail::get_instance();
		// Load config
		$config_bin = $rcmail->config->get("getmail_bin", "/usr/bin/getmail");
		$config_dir = $rcmail->config->get("getmail_dir", "/var/getmail/");
		// Add / at the end of the Opath if missing
		if(substr($config_dir, strlen($config_dir)-1, 1) != "/")
			$config_dir.= "/";
		// Add sudo-command if getmail should run as other user
		$config_runas = $rcmail->config->get("getmail_runas", "");
		if($config_runas != "")
			$config_runas = "/usr/bin/sudo -u " . $config_runas . " ";
		$command = $config_runas.$config_bin . ' --getmaildir='.$config_dir.$this->username.'/'.$rcfiles;
		// Execute command
		$ret = shell_exec($command);
		// Count number of Emails fetched
		$matches = array();
		preg_match_all("/([0-9]+) messages \([0-9]+ bytes\) retrieved/", $ret, $matches);
		$mails_fetched = 0;
		if($matches >= 1 && is_array($matches[1]))
			foreach($matches[1] as $number)
				$mails_fetched+= intval($number);
		// Send feedback to frontend
  		$rcmail->output->command('plugin.getmailactioncallback', array('message' => sprintf($this->gettext('running_done'), $mails_fetched)));
  		// Trigger mailbox-refresh
		$rcmail->output->command('refresh');
	}
}