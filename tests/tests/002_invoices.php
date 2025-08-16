<?php

test_start('create draft invoice');
try {
	$request = $lexware->create_invoice([
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
} 
catch (\Baebeca\LexwareException $e) {
	test($e->getMessage());
	test(print_r($e->getError(), true));
	test_finished(false);
}

// todo create finished and check status

// todo create finished, check status and downlaod pdf

test_start('invoice - 0% UST position');
try {
	$request = $lexware->create_invoice([
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
} 
catch (\Baebeca\LexwareException $e) {
	test($e->getMessage());
	test(print_r($e->getError(), true));
	test_finished(false);
}


test_start('invoice - 7% UST position');
try {
	$request = $lexware->create_invoice([
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
} 
catch (\Baebeca\LexwareException $e) {
	test($e->getMessage());
	test(print_r($e->getError(), true));
	test_finished(false);
}

test_start('invoice - 19% UST position');
try {
	$request = $lexware->create_invoice([
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
} 
catch (\Baebeca\LexwareException $e) {
	test($e->getMessage());
	test(print_r($e->getError(), true));
	test_finished(false);
}

test_start('invoice - 0%, 19, 7% UST position');
try {
	$request = $lexware->create_invoice([
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
} 
catch (\Baebeca\LexwareException $e) {
	test($e->getMessage());
	test(print_r($e->getError(), true));
	test_finished(false);
}

test_start('invoice - negative amount position');
try {
	$request = $lexware->create_invoice([
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
} 
catch (\Baebeca\LexwareException $e) {
	test($e->getMessage());
	test(print_r($e->getError(), true));
	test_finished(false);
}

test_start('invoice - zero amount');
try {
    $request = $lexware->create_invoice([
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
} 
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}

test_start('invoice - 100% discount');
try {
    $request = $lexware->create_invoice([
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
            'totalDiscountPercentage' => 100,
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
} 
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}


test_start('invoice - text position');
try {
	$request = $lexware->create_invoice([
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
} 
catch (\Baebeca\LexwareException $e) {
	test($e->getMessage());
	test(print_r($e->getError(), true));
	test_finished(false);
}


test_start('invoice - get all invoices');
$count_invoices_in_test_account = 0;
try {
	$request = $lexware->get_invoices_all();
	if (count($request)) {
        $count_invoices_in_test_account = count($request);
		test($count_invoices_in_test_account.' invoices in account');
		test_finished(true);
	} else {
		test_finished(false);
	}
}
catch(\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}


test_start('invoice - get last -5 invoices');
try {
	$request = $lexware->get_last_invoices(-5);
}
catch(\Baebeca\LexwareException $e) {
	if ($e->getMessage() == 'LexwareApi: positive invoice count needed') {
		test_finished(true);
	} else {
		test($e->getMessage());
		test(print_r($e->getError(), true));
		test_finished(false);
	}
}

if ($count_invoices_in_test_account >= 20) {
    test_start('invoice - get last 20 invoices');
    try {
        $request = $lexware->get_last_invoices(20);
        if (count($request) == 20) {
            test_finished(true);
        } else {
            test_finished(false);
        }
    }
    catch(\Baebeca\LexwareException $e) {
        test($e->getMessage());
        test(print_r($e->getError(), true));
        test_finished(false);
    }
}

if ($count_invoices_in_test_account >= 255) {
    test_start('invoice - get last 255 invoices');
    try {
        $request = $lexware->get_last_invoices(255);
        if (count($request) == 255) {
            test_finished(true);
        } else {
            test_finished(false);
        }
    }
    catch(\Baebeca\LexwareException $e) {
        test($e->getMessage());
        test(print_r($e->getError(), true));
        test_finished(false);
    }
}

test_start('invoice - get all (last '.$count_invoices_in_test_account.') invoices');
try {
	$request = $lexware->get_last_invoices($count_invoices_in_test_account);
	if (count($request) == $count_invoices_in_test_account) {
		test_finished(true);
	} else {
        test(count($request).' invoices returned');
        #test(print_r($request, true));
		test_finished(false);
	}
}
catch(\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}

test_start('invoice - 19% UST position with special chars in product');
try {
    $request = $lexware->create_invoice([
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
                'name' => 'WEBINAR BLUB ABC - Duminică, 01.01.2000 la ora 13.00',
                'description' => 'WEBINAR BLUB ABC - Duminică, 01.01.2000 la ora 13.00',
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
} catch(\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}

/** only for special tests
 * generate big amount ofg invoices to test webhooks
 */
/*
$amount = 50;
test_start('big amount of '.$amount.' invoices - 19% UST position');
try {
	for ($i = 0; $i < $amount; $i++) {
		$request = $lexware->create_invoice([
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
} catch(\Baebeca\LexwareException $e) {
	test($e->getMessage());
	test(print_r($e->getError(), true));
	test_finished(false);
}
test_finished(true);
*/