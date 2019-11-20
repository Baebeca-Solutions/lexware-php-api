<?php
test_start('create draft invoice and download pdf (not possible)');
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
			'shippingDate' => date('Y-m-d').'T00:00:01.000+02:00',
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
			$lexoffice->get_invoice_pdf('7f0f0f7f-dd61-4bf7-a9f7-a67b0530c7e9', 'test.pdf');
		} catch (lexoffice_exception $e) {
			test($e->getMessage());
			if ($e->getMessage() == 'lexoffice-php-api: error in api request - check details via $e->get_error()') {
				test_finished(true);
			} else {
				test_finished(false);
			}
		}
	} else {
		test_finished(false);
	}
} catch (lexoffice_exception $e) {
	test($e->getMessage());
	test_finished(false);
}
