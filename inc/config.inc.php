<?php	
	// Defining MSSQL Data
	define('MSSQL_DSN', 'WIN-CM7N6UU1HM0\SQLEXPRESS');
	define('MSSQL_USER', 'sa');
	define('MSSQL_PASS', '1Malevolent1Fusion2');
	
	// Connecting to MSSQL (ODBC)
	$mssql = odbc_connect('Driver={SQL Server};Server='.MSSQL_DSN.';', MSSQL_USER, MSSQL_PASS);
	
	// Including files
	include('functions.inc.php');
	
	// Config
	$_CONFIG['webtitle'] = getConfigValue('webtitle', 'Flyff Website');
	$_CONFIG['admtitle'] = getConfigValue('admtitle', 'Administration Panel');
	$_CONFIG['pwdsalt'] = getConfigValue('pwdsalt', 'mflyff');
	$_CONFIG['noreply'] = getConfigValue('noreplymail', 'noreply@localhost.com');
	$_CONFIG['ppemail'] = getConfigValue('ppemail', 'email@web.de');
	$_CONFIG['rate_exp'] = getConfigValue('rate_exp', '100x');
	$_CONFIG['rate_drop'] = getConfigValue('rate_drop', '100x');
	$_CONFIG['rate_penya'] = getConfigValue('rate_penya', '100x');
	$_CONFIG['teamspeak_ip'] = getConfigValue('teamspeak_ip', '12.34.567.89');
	$_CONFIG['teamspeak_port'] = getConfigValue('teamspeak_port', '1337');
	
	// PaySafeCard: Euro => Points
	$psc_values = array('10,00' => '1500', '25,00' => '4050', '50,00' => '8400', '100,00' => '18000');
?>