<?php
/**
 * @package     \baebeca\lexware-php-api
 * @copyright	Baebeca Solutions GmbH
 * @author		Sebastian Hayer-Lutz
 * @email		slu@baebeca.de
 * @link		https://github.com/Baebeca-Solutions/lexware-php-api
 * @license		AGPL-3.0 and Commercial
 * @license 	If you need a commercial license for your closed-source project check: https://github.com/Baebeca-Solutions/lexware-php-api/blob/php-8.4/LICENSE-commercial_EN.md
 **/

require __DIR__.'/../src/LexwareApi.php';
require __DIR__.'/../src/LexwareException.php';
use \Baebeca\LexwareApi;
use \Baebeca\LexwareException;

// if local developer test include secret api keys
if (is_file(__DIR__.'/_local_settings.php')) {
	require(__DIR__.'/_local_settings.php');
} else {
    echo PHP_EOL;
	echo 'no /tests/_local_settings.php found'.PHP_EOL;
	echo 'check the file /tests/_local_settings_default.php'.PHP_EOL;
    exit();
}

if (!isset($sandbox)) exit('$sandbox not defined');
if (!isset($api_key)) exit('$api_key not defined');
if (!isset($api_key_OSS_DESTINATION)) exit('$api_key_OSS_DESTINATION not defined');
if (!isset($run_specific_test)) exit('$run_specific_test not defined');
if (!isset($debug)) exit('$debug not defined');
if (!isset($xRechnungSampleId)) exit('$xRechnungSampleId not defined');
if (!isset($create_tons_of_customers)) exit('$create_tons_of_customers not defined');

// current german taxrates
$taxrate_19 = 19;
$taxrate_7 = 7;
/** local test configuration */

if (!isset($sandbox)) $sandbox = false;
$lexware = new \Baebeca\LexwareApi(array(
	'api_key' => $api_key,
	'ssl_verify' => false,
	'sandbox' => $sandbox,
));
$lexware_OSS_ORIGIN =& $lexware;

$lexware_OSS_DESTINATION = new \Baebeca\LexwareApi(array(
    'api_key' => $api_key_OSS_DESTINATION,
    'ssl_verify' => false,
    'sandbox' => $sandbox,
));;


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
test('include test: '.'./tests/000_init.php', true);
require_once ('./tests/000_init.php');

foreach ($tests as $test) {
    if ($test === '000_init.php') continue;
	$test_tmp = explode('_', $test);
	if (empty($run_specific_test) || $run_specific_test == (int)$test_tmp[0]) {
		if (substr($test, -4) != '.php') continue;
		test('include test: '.'./tests/'.$test, true);
		require_once ('./tests/'.$test);
	} else {
		test('skip include test: '.'./tests/'.$test);
	}
}