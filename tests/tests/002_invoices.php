<?php

test_start('create draft invoice');
try {
	$request = $lexoffice->create_invoice([
		'voucherDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
		'introduction' => 'Einleitungstext',
		'remark' => "Fußzeile\r\nMehrzeilig",
		'address' => [
			#'contactId' => '<id>',
			'name' => 'Frau Jane Doe',
			'street' => 'Str. 1',
			'zip' => '12345',
			'city' => 'Stadt',
			'countryCode' => 'DE',
		],
		'lineItems' => [
			[
				'type' => 'custom',
				'name' => 'Produktname',
				'description' => 'Beschreibung',
				'quantity' => 1,
				'unitName' => 'Stück',
				'unitPrice' => [
					'currency' => 'EUR',
					'netAmount' => 11.99,
					'taxRatePercentage' => $taxrate_19,
				],
				#'discountPercentage' => 0,
			],
		],
		'totalPrice' => [
			'currency' => 'EUR',
			#'totalDiscountAbsolute' => 0,
			#'totalDiscountPercentage' => 0,
		],
		'taxConditions' => [
			'taxType' => 'net',
		],
		'shippingConditions' => [
			'shippingDate' => date(DATE_RFC3339_EXTENDED),
			'shippingType' => 'delivery',
		],
		'paymentConditions' => [
			'paymentTermLabel' => 'Vorkasse',
			'paymentTermDuration' => 1,
		],
	], false);

	if ($request->id) {
		test('draft invoice created - id: '.$request->id);
		// todo check if status is draft

		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}

// todo create finished and check status

// todo create finished, check status and downlaod pdf

test_start('invoice - 0% UST position');
try {
	$request = $lexoffice->create_invoice([
		'voucherDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
		'introduction' => 'Einleitungstext',
		'remark' => "Fußzeile\r\nMehrzeilig",
		'address' => [
			#'contactId' => '<id>',
			'name' => 'Frau Jane Doe',
			'street' => 'Str. 1',
			'zip' => '12345',
			'city' => 'Stadt',
			'countryCode' => 'DE',
		],
		'lineItems' => [
			[
				'type' => 'custom',
				'name' => 'Produktname',
				'description' => 'Beschreibung',
				'quantity' => 1,
				'unitName' => 'Stück',
				'unitPrice' => [
					'currency' => 'EUR',
					'netAmount' => 11.99,
					'taxRatePercentage' => 0,
				],
				#'discountPercentage' => 0,
			],
		],
		'totalPrice' => [
			'currency' => 'EUR',
			#'totalDiscountAbsolute' => 0,
			#'totalDiscountPercentage' => 0,
		],
		'taxConditions' => [
			'taxType' => 'net',
		],
		'shippingConditions' => [
			'shippingDate' => date(DATE_RFC3339_EXTENDED),
			'shippingType' => 'delivery',
		],
		'paymentConditions' => [
			'paymentTermLabel' => 'Vorkasse',
			'paymentTermDuration' => 1,
		],
	], true);

	if ($request->id) {
		test('invoice created - id: '.$request->id);

		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}


test_start('invoice - 7% UST position');
try {
	$request = $lexoffice->create_invoice([
		'voucherDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
		'introduction' => 'Einleitungstext',
		'remark' => "Fußzeile\r\nMehrzeilig",
		'address' => [
			#'contactId' => '<id>',
			'name' => 'Frau Jane Doe',
			'street' => 'Str. 1',
			'zip' => '12345',
			'city' => 'Stadt',
			'countryCode' => 'DE',
		],
		'lineItems' => [
			[
				'type' => 'custom',
				'name' => 'Produktname',
				'description' => 'Beschreibung',
				'quantity' => 1,
				'unitName' => 'Stück',
				'unitPrice' => [
					'currency' => 'EUR',
					'netAmount' => 11.99,
					'taxRatePercentage' => $taxrate_7,
				],
				#'discountPercentage' => 0,
			],
		],
		'totalPrice' => [
			'currency' => 'EUR',
			#'totalDiscountAbsolute' => 0,
			#'totalDiscountPercentage' => 0,
		],
		'taxConditions' => [
			'taxType' => 'net',
		],
		'shippingConditions' => [
			'shippingDate' => date(DATE_RFC3339_EXTENDED),
			'shippingType' => 'delivery',
		],
		'paymentConditions' => [
			'paymentTermLabel' => 'Vorkasse',
			'paymentTermDuration' => 1,
		],
	], true);

	if ($request->id) {
		test('invoice created - id: '.$request->id);

		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}

test_start('invoice - 19% UST position');
try {
	$request = $lexoffice->create_invoice([
		'voucherDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
		'introduction' => 'Einleitungstext',
		'remark' => "Fußzeile\r\nMehrzeilig",
		'address' => [
			#'contactId' => '<id>',
			'name' => 'Frau Jane Doe',
			'street' => 'Str. 1',
			'zip' => '12345',
			'city' => 'Stadt',
			'countryCode' => 'DE',
		],
		'lineItems' => [
			[
				'type' => 'custom',
				'name' => 'Produktname',
				'description' => 'Beschreibung',
				'quantity' => 1,
				'unitName' => 'Stück',
				'unitPrice' => [
					'currency' => 'EUR',
					'netAmount' => 11.99,
					'taxRatePercentage' => $taxrate_19,
				],
				#'discountPercentage' => 0,
			],
		],
		'totalPrice' => [
			'currency' => 'EUR',
			#'totalDiscountAbsolute' => 0,
			#'totalDiscountPercentage' => 0,
		],
		'taxConditions' => [
			'taxType' => 'net',
		],
		'shippingConditions' => [
			'shippingDate' => date(DATE_RFC3339_EXTENDED),
			'shippingType' => 'delivery',
		],
		'paymentConditions' => [
			'paymentTermLabel' => 'Vorkasse',
			'paymentTermDuration' => 1,
		],
	], true);

	if ($request->id) {
		test('invoice created - id: '.$request->id);

		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}


test_start('invoice - 0%, 19, 7% UST position');
try {
	$request = $lexoffice->create_invoice([
		'voucherDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
		'introduction' => 'Einleitungstext',
		'remark' => "Fußzeile\r\nMehrzeilig",
		'address' => [
			#'contactId' => '<id>',
			'name' => 'Frau Jane Doe',
			'street' => 'Str. 1',
			'zip' => '12345',
			'city' => 'Stadt',
			'countryCode' => 'DE',
		],
		'lineItems' => [
			[
				'type' => 'custom',
				'name' => 'Produktname',
				'description' => 'Beschreibung',
				'quantity' => 1,
				'unitName' => 'Stück',
				'unitPrice' => [
					'currency' => 'EUR',
					'netAmount' => 11.99,
					'taxRatePercentage' => 0,
				],
				#'discountPercentage' => 0,
			],
			[
				'type' => 'custom',
				'name' => 'Produktname',
				'description' => 'Beschreibung',
				'quantity' => 1,
				'unitName' => 'Stück',
				'unitPrice' => [
					'currency' => 'EUR',
					'netAmount' => 11.99,
					'taxRatePercentage' => $taxrate_7,
				],
				#'discountPercentage' => 0,
			],
			[
				'type' => 'custom',
				'name' => 'Produktname',
				'description' => 'Beschreibung',
				'quantity' => 1,
				'unitName' => 'Stück',
				'unitPrice' => [
					'currency' => 'EUR',
					'netAmount' => 11.99,
					'taxRatePercentage' => $taxrate_19,
				],
				#'discountPercentage' => 0,
			],
		],
		'totalPrice' => [
			'currency' => 'EUR',
			#'totalDiscountAbsolute' => 0,
			#'totalDiscountPercentage' => 0,
		],
		'taxConditions' => [
			'taxType' => 'net',
		],
		'shippingConditions' => [
			'shippingDate' => date(DATE_RFC3339_EXTENDED),
			'shippingType' => 'delivery',
		],
		'paymentConditions' => [
			'paymentTermLabel' => 'Vorkasse',
			'paymentTermDuration' => 1,
		],
	], true);

	if ($request->id) {
		test('invoice created - id: '.$request->id);

		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}

test_start('invoice - negative amount position');
try {
	$request = $lexoffice->create_invoice([
		'voucherDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
		'introduction' => 'Einleitungstext',
		'remark' => "Fußzeile\r\nMehrzeilig",
		'address' => [
			#'contactId' => '<id>',
			'name' => 'Frau Jane Doe',
			'street' => 'Str. 1',
			'zip' => '12345',
			'city' => 'Stadt',
			'countryCode' => 'DE',
		],
		'lineItems' => [
			[
				'type' => 'custom',
				'name' => 'Produktname',
				'description' => 'Beschreibung',
				'quantity' => 1,
				'unitName' => 'Stück',
				'unitPrice' => [
					'currency' => 'EUR',
					'netAmount' => 11.99,
					'taxRatePercentage' => $taxrate_19,
				],
				#'discountPercentage' => 0,
			],
			[
				'type' => 'custom',
				'name' => 'Produktname',
				'description' => 'Beschreibung',
				'quantity' => 1,
				'unitName' => 'Stück',
				'unitPrice' => [
					'currency' => 'EUR',
					'netAmount' => -11.99,
					'taxRatePercentage' => $taxrate_19,
				],
				#'discountPercentage' => 0,
			],
			[
				'type' => 'custom',
				'name' => 'Produktname',
				'description' => 'Beschreibung',
				'quantity' => 1,
				'unitName' => 'Stück',
				'unitPrice' => [
					'currency' => 'EUR',
					'netAmount' => 3.99,
					'taxRatePercentage' => $taxrate_19,
				],
				#'discountPercentage' => 0,
			],
		],
		'totalPrice' => [
			'currency' => 'EUR',
			#'totalDiscountAbsolute' => 0,
			#'totalDiscountPercentage' => 0,
		],
		'taxConditions' => [
			'taxType' => 'net',
		],
		'shippingConditions' => [
			'shippingDate' => date(DATE_RFC3339_EXTENDED),
			'shippingType' => 'delivery',
		],
		'paymentConditions' => [
			'paymentTermLabel' => 'Vorkasse',
			'paymentTermDuration' => 1,
		],
	], true);

	if ($request->id) {
		test('invoice created - id: '.$request->id);

		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}

test_start('invoice - text position');
try {
	$request = $lexoffice->create_invoice([
		'voucherDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
		'introduction' => 'Einleitungstext',
		'remark' => "Fußzeile\r\nMehrzeilig",
		'address' => [
			#'contactId' => '<id>',
			'name' => 'Frau Jane Doe',
			'street' => 'Str. 1',
			'zip' => '12345',
			'city' => 'Stadt',
			'countryCode' => 'DE',
		],
		'lineItems' => [
			[
				'type' => 'text',
				'name' => 'blub blub bub',
				'description' => 'Beschreibung',
			],
			[
				'type' => 'custom',
				'name' => 'Produktname',
				'description' => 'Beschreibung',
				'quantity' => 1,
				'unitName' => 'Stück',
				'unitPrice' => [
					'currency' => 'EUR',
					'netAmount' => 11.99,
					'taxRatePercentage' => $taxrate_19,
				],
				#'discountPercentage' => 0,
			],
			[
				'type' => 'custom',
				'name' => 'Produktname',
				'description' => 'Beschreibung',
				'quantity' => 1,
				'unitName' => 'Stück',
				'unitPrice' => [
					'currency' => 'EUR',
					'netAmount' => -3.99,
					'taxRatePercentage' => $taxrate_19,
				],
				#'discountPercentage' => 0,
			],
		],
		'totalPrice' => [
			'currency' => 'EUR',
			#'totalDiscountAbsolute' => 0,
			#'totalDiscountPercentage' => 0,
		],
		'taxConditions' => [
			'taxType' => 'net',
		],
		'shippingConditions' => [
			'shippingDate' => date(DATE_RFC3339_EXTENDED),
			'shippingType' => 'delivery',
		],
		'paymentConditions' => [
			'paymentTermLabel' => 'Vorkasse',
			'paymentTermDuration' => 1,
		],
	], true);

	if ($request->id) {
		test('invoice created - id: '.$request->id);
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}


test_start('invoice - get all invoices');
$max_invoices_in_test_account = 0;
try {
	$request = $lexoffice->get_invoices_all();
	if (count($request)) {
		$max_invoices_in_test_account = count($request);
		test($max_invoices_in_test_account.' invoices in account');
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(lexoffice_exception $e) {
	if ($e->getMessage() == 'lexoffice-php-api: positive invoice count needed') {
		test_finished(true);
	} else {
		test($e->getMessage());
		test(print_r($e->get_error(), true));
		test_finished(false);
	}
}


test_start('invoice - get last -5 invoices');
try {
	$request = $lexoffice->get_last_invoices(-5);
} catch(lexoffice_exception $e) {
	if ($e->getMessage() == 'lexoffice-php-api: positive invoice count needed') {
		test_finished(true);
	} else {
		test($e->getMessage());
		test(print_r($e->get_error(), true));
		test_finished(false);
	}
}

test_start('invoice - get last 20 invoices');
try {
	$request = $lexoffice->get_last_invoices(20);
	if (count($request) == 20) {
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(lexoffice_exception $e) {
	if ($e->getMessage() == 'lexoffice-php-api: positive invoice count needed') {
		test_finished(true);
	} else {
		test($e->getMessage());
		test(print_r($e->get_error(), true));
		test_finished(false);
	}
}

test_start('invoice - get last 100 invoices');
try {
	$request = $lexoffice->get_last_invoices(100);
	if (count($request) == 100) {
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(lexoffice_exception $e) {
	if ($e->getMessage() == 'lexoffice-php-api: positive invoice count needed') {
		test_finished(true);
	} else {
		test($e->getMessage());
		test(print_r($e->get_error(), true));
		test_finished(false);
	}
}

test_start('invoice - get last 120 invoices');
try {
	$request = $lexoffice->get_last_invoices(120);
	if (count($request) == 120) {
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(lexoffice_exception $e) {
	if ($e->getMessage() == 'lexoffice-php-api: positive invoice count needed') {
		test_finished(true);
	} else {
		test($e->getMessage());
		test(print_r($e->get_error(), true));
		test_finished(false);
	}
}

test_start('invoice - get last '.$max_invoices_in_test_account.' invoices');
try {
	$request = $lexoffice->get_last_invoices($max_invoices_in_test_account);
	if (count($request) == $max_invoices_in_test_account) {
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(lexoffice_exception $e) {
	if ($e->getMessage() == 'lexoffice-php-api: positive invoice count needed') {
		test_finished(true);
	} else {
		test($e->getMessage());
		test(print_r($e->get_error(), true));
		test_finished(false);
	}
}

/** only for special tests
 * generate big amount ofg invoices to test webhooks
 */
/*
$amount = 50;
test_start('big amount of '.$amount.' invoices - 19% UST position');
try {
	for ($i = 0; $i < $amount; $i++) {
		$request = $lexoffice->create_invoice([
			'voucherDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
			'introduction' => 'Einleitungstext',
			'remark' => "Fußzeile\r\nMehrzeilig",
			'address' => [
				#'contactId' => '<id>',
				'name' => 'Frau Jane Doe',
				'street' => 'Str. 1',
				'zip' => '12345',
				'city' => 'Stadt',
				'countryCode' => 'DE',
			],
			'lineItems' => [
				[
					'type' => 'custom',
					'name' => 'Produktname',
					'description' => 'Beschreibung',
					'quantity' => 1,
					'unitName' => 'Stück',
					'unitPrice' => [
						'currency' => 'EUR',
						'netAmount' => 11.99,
						'taxRatePercentage' => $taxrate_19,
					],
					#'discountPercentage' => 0,
				],
			],
			'totalPrice' => [
				'currency' => 'EUR',
				#'totalDiscountAbsolute' => 0,
				#'totalDiscountPercentage' => 0,
			],
			'taxConditions' => [
				'taxType' => 'net',
			],
			'shippingConditions' => [
				'shippingDate' => date(DATE_RFC3339_EXTENDED),
				'shippingType' => 'delivery',
			],
			'paymentConditions' => [
				'paymentTermLabel' => 'Vorkasse',
				'paymentTermDuration' => 1,
			],
		], true);

		if (!isset($request->id) || empty($request->id)) {
			test_finished(false);
		}
	}
} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}
test_finished(true);
*/