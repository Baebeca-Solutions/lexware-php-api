<?php

test_start('create article PRODUCT');
try {
	$request = $lexware->create_article([
		'title' => 'Testartikel',
		'description' => 'Beschreibung',
		'type' => 'PRODUCT',
		'unitName' => 'Stück',
		'price' => [
            'leadingPrice' => 'NET',
            'netPrice' => 44.99,
            'taxRate' => $taxrate_19,
		],
	]);

	if ($request->id) {
		test('article created - id: '.$request->id);
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(\Baebeca\LexwareException $e) {
	test($e->getMessage());
	test(print_r($e->getError(), true));
	test_finished(false);
}

test_start('create article SERVICE');
try {
	$request = $lexware->create_article([
		'title' => 'Testservice',
        'description' => 'Beschreibung',
		'type' => 'SERVICE',
		'unitName' => 'Stück',
		'price' => [
            'leadingPrice' => 'NET',
            'netPrice' => 44.99,
            'taxRate' => $taxrate_19,
		],
	]);

	if ($request->id) {
		test('article created - id: '.$request->id);
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(\Baebeca\LexwareException $e) {
	test($e->getMessage());
	test(print_r($e->getError(), true));
	test_finished(false);
}