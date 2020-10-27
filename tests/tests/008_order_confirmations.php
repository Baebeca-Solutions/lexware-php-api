<?php

test_start('create order_confirmation without contact');
try {
	$request = $lexoffice->create_orderconfirmation([
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
	]);

	if ($request->id) {
		test('order_confirmation created - id: '.$request->id);
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}

test_start('create order_confirmation with contact');
try {
	$random_contact_name = 'contact_'.rand(11111111, 999999999999);
	try {
		$request_contact = $lexoffice->create_contact(array(
			'version' => 0,
			'roles' => array(
				'customer' => array(
					'number' => '',
				),
			),
			'company' => array(
				// use random name, use it in later search check
				'name' => $random_contact_name,
				'street' => 'Genklerhardt 6',
				'zip' => '51647',
				'city' => 'Gummersbach',
				'countryCode' => 'DE',
				'contactPersons' => array(
					array(
						'salutation' => 'Herr',
						'firstName' => 'John',
						'lastName' => 'Doe',
						'emailAddress' =>'support@baebeca.de',
						'phoneNumber' => '022619202930',
					)
				),
			),
			'addresses' => array(
				'billing' => array(
					array(
						'street' => 'Genklerhardt 6',
						'zip' => '51647',
						'city' => 'Gummersbach',
						'countryCode' => 'DE',
					),
				),
				'shipping' => array(
					array(
						'street' => 'Genklerhardt 6',
						'zip' => '51647',
						'city' => 'Gummersbach',
						'countryCode' => 'DE',
					),
				),
			),
			'emailAddresses' => array(
				'business' => array(
					'support@baebeca.de'
				),
			),
			'phoneNumbers' => array(
				'business' => array(
					'022619202930'
				),
			),
			'note' => '',
		));

		if (!$request_contact->id) {
			test_finished(false);
		}

	} catch(lexoffice_exception $e) {
		test($e->getMessage());
		test(print_r($e->get_error(), true));
		test_finished(false);
	}


	$request = $lexoffice->create_orderconfirmation([
		'voucherDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
		'introduction' => 'Einleitungstext',
		'remark' => "Fußzeile\r\nMehrzeilig",
		'address' => [
			'contactId' => $request_contact->id,
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
	]);

	if ($request->id) {
		test('order_confirmation created - id: '.$request->id);
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}

test_start('order_confirmation - 0% UST position');
try {
	$request = $lexoffice->create_orderconfirmation([
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
	]);

	if ($request->id) {
		test('order_confirmation created - id: '.$request->id);

		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}


test_start('order_confirmation - 7% UST position');
try {
	$request = $lexoffice->create_orderconfirmation([
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
	]);

	if ($request->id) {
		test('order_confirmation created - id: '.$request->id);

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
	$request = $lexoffice->create_orderconfirmation([
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
	]);

	if ($request->id) {
		test('order_confirmation created - id: '.$request->id);

		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}


test_start('order_confirmation - 0%, 19, 7% UST position');
try {
	$request = $lexoffice->create_orderconfirmation([
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
	]);

	if ($request->id) {
		test('order_confirmation created - id: '.$request->id);

		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}

test_start('order_confirmation - negative amount position');
try {
	$request = $lexoffice->create_orderconfirmation([
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
		test('order_confirmation created - id: '.$request->id);

		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}

test_start('order_confirmation - text position');
try {
	$request = $lexoffice->create_orderconfirmation([
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
	]);

	if ($request->id) {
		test('order_confirmation created - id: '.$request->id);
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}

