<?php
test_start('invalid api key');
$lexoffice = new lexoffice_client(array(
	'api_key' => 'roflcopter',
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