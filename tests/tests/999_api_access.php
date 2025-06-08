<?php
test_start('invalid api key');
$lexware = new \Baebeca\LexwareApi(array(
	'api_key' => 'roflcopter',
	'ssl_verify' => false,
));

try {
	$request = $lexware->get_profile();
} catch (\Baebeca\LexwareException $e) {
	if ($e->getMessage() == 'LexwareApi: invalid API Key') {
		test_finished(true);
	} else {
		test_finished(false);
	}
}

test_start('incorrect api key');
$lexware = new \Baebeca\LexwareApi(array(
	'api_key' => 'f059d449-504e-4786-bf16-d1ef03b589af',
	'ssl_verify' => false,
));

try {
	$request = $lexware->get_profile();
} catch (\Baebeca\LexwareException $e) {
	if ($e->getMessage() == 'LexwareApi: invalid API Key') {
		test_finished(true);
	} else {
		test_finished(false);
	}
}


test_start('empty api key');
try {
    $lexware = new \Baebeca\LexwareApi(array(
        'api_key' => '',
        'ssl_verify' => false,
    ));
} catch (\Baebeca\LexwareException $e) {
	if ($e->getMessage() == 'LexwareApi: no api_key is given') {
		test_finished(true);
	} else {
		test_finished(false);
	}
}

test_start('false api key');
try {
    $lexware = new \Baebeca\LexwareApi(array(
        'api_key' => false,
        'ssl_verify' => false,
    ));
} catch (\Baebeca\LexwareException $e) {
	if ($e->getMessage() == 'LexwareApi: no api_key is given') {
		test_finished(true);
	} else {
		test_finished(false);
	}
}

test_start('true api key');
$lexware = new \Baebeca\LexwareApi(array(
	'api_key' => true,
	'ssl_verify' => false,
));

try {
	$request = $lexware->get_profile();
} catch (\Baebeca\LexwareException $e) {
	if ($e->getMessage() == 'LexwareApi: invalid API Key') {
		test_finished(true);
	} else {
		test_finished(false);
	}
}