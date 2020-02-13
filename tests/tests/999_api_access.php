<?php
test_start('invalid api key');
$lexoffice = new lexoffice_client(array(
	'api_key' => 'roflcopter',
	'ssl_verify' => false,
));

try {
	$request = $lexoffice->get_profile();
} catch (lexoffice_exception $e) {
	if ($e->getMessage() == 'lexoffice-php-api: invalid API Key format') {
		test_finished(true);
	} else {
		test_finished(false);
	}
}

test_start('incorrect api key');
$lexoffice = new lexoffice_client(array(
	'api_key' => 'f059d449-504e-4786-bf16-d1ef03b589af',
	'ssl_verify' => false,
));

try {
	$request = $lexoffice->get_profile();
} catch (lexoffice_exception $e) {
	if ($e->getMessage() == 'lexoffice-php-api: invalid API Key') {
		test_finished(true);
	} else {
		test_finished(false);
	}
}


test_start('empty api key');
$lexoffice = new lexoffice_client(array(
	'api_key' => '',
	'ssl_verify' => false,
));

try {
	$request = $lexoffice->get_profile();
} catch (lexoffice_exception $e) {
	if ($e->getMessage() == 'lexoffice-php-api: missing/empty API Key') {
		test_finished(true);
	} else {
		test_finished(false);
	}
}

test_start('false api key');
$lexoffice = new lexoffice_client(array(
	'api_key' => false,
	'ssl_verify' => false,
));

try {
	$request = $lexoffice->get_profile();
} catch (lexoffice_exception $e) {
	if ($e->getMessage() == 'lexoffice-php-api: missing/empty API Key') {
		test_finished(true);
	} else {
		test_finished(false);
	}
}

test_start('true api key');
$lexoffice = new lexoffice_client(array(
	'api_key' => true,
	'ssl_verify' => false,
));

try {
	$request = $lexoffice->get_profile();
} catch (lexoffice_exception $e) {
	if ($e->getMessage() == 'lexoffice-php-api: missing/empty API Key') {
		test_finished(true);
	} else {
		test_finished(false);
	}
}