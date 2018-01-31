<?php
/*
	starts a session if no session has been started.
	Included in all php files.
	REFERENCE: http://stackoverflow.com/questions/6914275/php-session-start-with-include-files
	AUTHOR:
	Evan Mulawski
*/
if (!isset($_SESSION)){
	session_start();
}