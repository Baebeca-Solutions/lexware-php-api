<?php

test_start('create big voucher');
try {
	$request = $lexoffice->create_voucher([
		'voucherDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
		'type' => 'salesinvoice',
		'voucherNumber' => "Test-1234",
		'totalGrossAmount' => 25251578.84,
		'totalTaxAmount' => 4031764.69,
		'taxType' => "gross",
		'useCollectiveContact' => true,
		'voucherItems' => [
			[
				'amount' => 25251578.84,
				'taxAmount' => 4031764.69,
				'taxRatePercent' => $taxrate_19,
				'categoryId' => '8f8664a1-fd86-11e1-a21f-0800200c9a66',
			],
		],
	]);

	if ($request->id) {
		test('voucher created - id: '.$request->id);
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}

test_start('create mixed tax voucher 16/19');
try {
    $request = $lexoffice->create_voucher([
        'version' => 0,
        'voucherDate' => '2021-02-27',
        'dueDate' => '2021-03-06',
        'type' => 'salesinvoice',
        'voucherNumber' => "21-001003RP",
        'useCollectiveContact' => true,
        'totalGrossAmount' => 9974.69,
        'voucherItems' => [
            [
                'amount' => "1748.81",
                'taxAmount' => "279.81",
                'taxRatePercent' => 16,
                'categoryId' => '8f8664a1-fd86-11e1-a21f-0800200c9a66',
            ],
            [
                'amount' => "6677.37",
                'taxAmount' => "1268.70",
                'taxRatePercent' => 19,
                'categoryId' => '8f8664a1-fd86-11e1-a21f-0800200c9a66',
            ],
        ],
        'taxType' => "net",
        'totalTaxAmount' => '1548.51',
        'remark' => 'Erdbeerkuchen',
    ]);

    if ($request->id) {
        test('voucher created - id: '.$request->id);
        test_finished(true);
    } else {
        test_finished(false);
    }
} catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}

test_start('create voucher #74228');
try {
    $request = $lexoffice->create_voucher([
        'version' => 0,
        'type' => 'salesinvoice',
        'voucherNumber' => '6126',
        'voucherDate' => '2021-09-13',
        'dueDate' => '2021-09-20',
        'useCollectiveContact' => true,
        'totalGrossAmount' => 175.37,
        'taxType' => "gross",
        'totalTaxAmount' => 28.00,
        'voucherItems' => [
            [
                'amount' => 175.37,
                'taxAmount' => 28.00,
                'taxRatePercent' => 19,
                'categoryId' => '8f8664a1-fd86-11e1-a21f-0800200c9a66',
            ],
        ],
    ]);

    if ($request->id) {
        test('voucher created - id: '.$request->id);
        test_finished(true);
    } else {
        test_finished(false);
    }
} catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}

test_start('get all vouchers');
try {
    $request = $lexoffice->get_vouchers('salesinvoice', 'draft,open,paid,paidoff,voided,transferred,sepadebit,accepted,rejected', 'both');

    if (count($request)) {
        test('get '.count($request).' vouchers');
        test_finished(true);
    } else {
        test_finished(false);
    }
} catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}