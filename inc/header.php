<?php
	session_start();
	error_reporting(0);
	include('inc/config.inc.php');
	if(isset($_SESSION['user'])) {
		$loggedin = 1;
	 } else {
		$loggedin = 0;
	}
	$user = $_SESSION['user'];
	if($loggedin != 1) {
		$active_user = 0;
	}
?>