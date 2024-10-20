<?php

test_start('create article PRODUCT');
try {
	$request = $lexoffice->create_article([
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
} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}

test_start('create article SERVICE');
try {
	$request = $lexoffice->create_article([
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
} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}