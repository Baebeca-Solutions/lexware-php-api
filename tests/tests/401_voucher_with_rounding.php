<?php

test_start('voucher with missing rounding #105687');
try {
	$request = $lexoffice->create_voucher([
		'voucherDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
		'type' => 'salesinvoice',
		'voucherNumber' => "Test-1234",
		'totalGrossAmount' => 134,
		'totalTaxAmount' => 13,
		'taxType' => "net",
		'useCollectiveContact' => true,
		'voucherItems' => [
            [
                'amount' => 37.79,
                'taxAmount' => 7.18,
                'taxRatePercent' => 19,
                'categoryId' => '8f8664a1-fd86-11e1-a21f-0800200c9a66',
            ],
            [
                'amount' => 83.14,
                'taxAmount' => 5.82,
                'taxRatePercent' => 7,
                'categoryId' => '8f8664a1-fd86-11e1-a21f-0800200c9a66',
            ],
            [
                'amount' => 0.07,
                'taxAmount' => 0,
                'taxRatePercent' => 0,
                'categoryId' => 'aba9020f-d0a6-47ca-ace6-03d6ed492351',
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