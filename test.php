<?php
require_once (__DIR__.'/lexoffice-php-api.php');

// if local developer test include secret api keys
if (is_file(__DIR__.'/_local_test_settings.php')) {
	require_once (__DIR__.'/_local_test_settings.php');
} else {
	$api_key = '';
}

$lexoffice = new lexoffice_client(array(
	'api_key' => $api_key
));

#print_r($lexoffice->get_events_all());

#print_r($lexoffice->add_event('contact.created'));

#print_r($lexoffice->delete_event('a8a0a5a6-0dc1-4c9b-bfaa-7de4d4a3d6a5'));

#echo '<pre>'.print_r($lexoffice->get_invoice('7f0f0f7f-dd61-4bf7-a9f7-a67b0530c7e9'), true).'</pre>';

#$invoice = $lexoffice->get_invoice('350d7ea4-63f9-44fb-a404-b4de167b4a8e');
#echo $invoice->voucherNumber;

#$lexoffice->get_invoice_pdf('7f0f0f7f-dd61-4bf7-a9f7-a67b0530c7e9', 'test.pdf'), true);

/*
print_r($lexoffice->create_invoice(array(
	'voucherDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
	'introduction' => 'Einleitungstext',
	'remark' => "Fußzeile\r\nMehrzeilig",
	'address' => array(
		#'contactId' => '<id>',
		'name' => 'Frau Jane Doe',
		'street' => 'Str. 1',
		'zip' => '12345',
		'city' => 'Stadt',
		'countryCode' => 'DE',
	),
	'lineItems' => array(
		array(
			'type' => 'custom',
			'name' => 'Produktname',
			'description' => 'Beschreibung',
			'quantity' => 1,
			'unitName' => 'Stück',
			'unitPrice' => array(
				'currency' => 'EUR',
				'netAmount' => 10.99,
        		'taxRatePercentage' => 19,
			),
			#'discountPercentage' => 0,
		),
	),
	'totalPrice' => array(
		'currency' => 'EUR',
		#'totalDiscountAbsolute' => 0,
		#'totalDiscountPercentage' => 0,
	),
	'taxConditions' => array(
		'taxType' => 'net',
	),
	'shippingConditions' => array(
		'shippingDate' => $date,
		'shippingType' => 'delivery',
	),
	'paymentConditions' => array(
		'paymentTermLabel' => 'Vorkasse',
		'paymentTermDuration' => 1,
	),
), false));
*/