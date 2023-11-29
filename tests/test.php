<?php
require_once(__DIR__.'/../lexoffice-php-api.php');

// if local developer test include secret api keys
if (is_file(__DIR__.'/_local_settings.php')) {
	require_once(__DIR__.'/_local_settings.php');
} else {
	exit('no "/tests/_local_settings.php" found');
}

/** local test configuration */
#$run_specific_test = 11;
$debug = true;

// enable your current oss config
#$oss_config = 'origin';
$oss_config = 'destination';

// current german taxrates
$taxrate_19 = 19;
$taxrate_7 = 7;
/** local test configuration */

if (!isset($sandbox)) $sandbox = false;
$lexoffice = new lexoffice_client(array(
	'api_key' => $api_key,
	'ssl_verify' => false,
	'sandbox' => $sandbox,
	'sandbox_sso' => $sandbox_sso,
));


$logfile_current_test = false;
$logfile_current_test_content = '';
function test_start($description) {
	global $logfile_current_test, $unit, $logfile_current_test_content;
	$logfile_current_test = uniqid();
	$logfile_current_test_content.= date('d.m.Y H:i:s')." [".$logfile_current_test."] - start new test - ".$logfile_current_test."\r\n";
	echo date('d.m.Y H:i:s')." [".$logfile_current_test."] - start new test - ".$logfile_current_test."\r\n";
	$logfile_current_test_content.= date('d.m.Y H:i:s')." [".$logfile_current_test."] - description: ".$description."\r\n";
	echo date('d.m.Y H:i:s')." [".$logfile_current_test."] - description: ".$description."\r\n";
}
function test($notice, $overide_debug = false) {
	global $logfile_current_test, $debug, $logfile_current_test_content;
	$logfile_current_test_content.= date('d.m.Y H:i:s')." [".$logfile_current_test."] - ".$notice."\r\n";
	if ($overide_debug || $debug) echo date('d.m.Y H:i:s')." [".$logfile_current_test."] - ".$notice."\r\n";
}
function test_finished($result) {
	global $logfile_current_test, $logfile_current_test_content;

	if ($result) {
		echo date('d.m.Y H:i:s')." [".$logfile_current_test."] ===> Testresult OK\r\n";
		echo "\r\n";
	} else {
		echo "\r\n==================== FAILED - last test output ====================\r\n";
		echo date('d.m.Y H:i:s').$logfile_current_test_content."\r\n";
		echo date('d.m.Y H:i:s')." [".$logfile_current_test."] ===> Testresult FAILED\r\n";
		echo "==================== ====================\r\n";
		exit();
	}
	$logfile_current_test = false;
	$logfile_current_test_content = '';
}

$tests = array_slice(scandir('./tests'), 2);
foreach ($tests as $test) {
	$test_tmp = explode('_', $test);
	if (empty($run_specific_test) || $run_specific_test == (int)$test_tmp[0]) {
		if (substr($test, -4) != '.php') continue;
		test('include test: '.'./tests/'.$test, true);
		require_once ('./tests/'.$test);
	} else {
		test('skip include test: '.'./tests/'.$test);
	}
}