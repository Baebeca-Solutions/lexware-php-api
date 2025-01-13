<?php

test_start('create invoice with multi line term label');
try {
    $label = "Kauf auf Rechnung\r\n\r\n";
    $label.= "Bitte nutzen Sie die folgenden Angaben für die Überweisung des Rechnungsbetrags:\r\n";
    $label.= "Betrag: 14.234,77 EUR\r\n";
    $label.= "IBAN: DE02120300000000202051\r\n";
    $label.= "BIC: BYLADEM1001\r\n";
    $label.= "Kontoinhaber: Kontinhaber Firma / Vorname / Nachname\r\n";
    $label.= "Kreditinstitut: 	DEUTSCHE KREDITBANK BERLIN\r\n";
    $label.= "Verwendungszweck: 022011100003429660	\r\n";
    $label.= "Zahlbar bis: 13.01.2025";


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
			'paymentTermLabel' => $label,
			'paymentTermDuration' => 1,
		],
	]);

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