getmailbutton
================
This plugin adds a Button to the toolbar which allows to trigger the getmail-command. There is no gui for configuration yet.
Author: Florian Bodenheimer (flobo@posteo.de)
https://github.com/fbodenheimer/getmailbutton

Released under the GPL license (http://www.gnu.org/licenses/gpl.txt)

Installation instructions:
--------------------------


1. Configuration-File
getmail_bin
	Path of the getmail-executable
getmail_dir
	Basedir where the rc-files are found. Must end with '/'
getmail_runas
	If you want to run getmail under another user than www-data you can specify the user here. If set the plugin calls sudo -u. Don't forget to configure user with "NOPASSWD" for the getmail command:
	Example: 	www-data ALL=(vmail) NOPASSWD: /usr/bin/getmail
				Allows www-data to tun getmail as user vmail without password promt.
permitted_users
	List of users that are allowed to use this plugin. Button is only shown if username is in list. The username has the value of the name the current user used for login. Each entry points to an
	array containing the available rc-files.
	Example:	array("flobo@fl0b0.net" => array("mail@domain1.de", "mail@domain2.eu", "mail@gmx.net"),
						"alice@l0b0.net" => array("alice2234@web.de")
						);
2. RC-Files
Place the rc-files in the getmail_dir/username/ - directory
Example:
	getmail_dir is /var/getmail/
	Files for flobo@flobo.net (see above) has to be placed in /var/getmail/flobo@flobo.net
	/var/getmail/flobo@flobo.net/mail@domain1.de
	/var/getmail/flobo@flobo.net/mail@domain2.eu
	/var/getmail/flobo@flobo.net/mail@gmx.net
	
	For alice@fl0b0.net
	/var/getmail/alice@flobo.net/alice2234@web.de
	
3. Usage
Press the getmail-Button. Messages are fetched and mailbox is refreshed
				