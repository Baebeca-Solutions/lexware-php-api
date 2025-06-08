<?php
test_start('create draft invoice and download pdf (not possible)');
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
					'netAmount' => 10.99,
					'taxRatePercentage' => 19,
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
		test('try download pdf');
		try {
			$lexware->get_invoice_pdf($request->id, 'test.pdf');
		} catch (\Baebeca\LexwareException $e) {
			test($e->getMessage());
			if ($e->getMessage() == 'LexwareApi: requested invoice is a draft. Cannot create/download pdf file. Check details via $e->getError()') {
				test_finished(true);
			} else {
				test_finished(false);
			}
		}
	} else {
		test_finished(false);
	}
} catch (\Baebeca\LexwareException $e) {
	test($e->getMessage());
	test_finished(false);
}
