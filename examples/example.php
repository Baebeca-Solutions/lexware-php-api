<?php
/**
 * @package     \baebeca\lexware-php-api
 * @copyright	Baebeca Solutions GmbH
 * @author		Sebastian Hayer-Lutz
 * @email		slu@baebeca.de
 * @link		https://github.com/Baebeca-Solutions/lexware-php-api
 * @license		AGPL-3.0 and Commercial
 * @license 	If you need a commercial license for your closed-source project check: https://github.com/Baebeca-Solutions/lexware-php-api/blob/php-8.4/LICENSE-commercial_EN.md
 **/

require __DIR__.'/../vendor/autoload.php';
use \Baebeca\LexwareApi;
use \Baebeca\LexwareException;

$lexware = new LexwareApi([
    'api_key' => 'my-api-key'
]);

// catch errors
try {
    $invoices = $lexware->get_last_invoices(-5);
}
catch (LexwareException $e) {
    var_dump($e->getMessage());
    print_r($e->getError());
}

// show active Webhooks
#print_r($lexware->get_events_all());

// create webhook
#print_r($lexware->add_event('contact.created', 'https://domain.tld/lexware-php-client/callback.php'));

// delete webhook
#print_r($lexware->delete_event('a8a0a5a6-0dc1-4c9b-bfaa-7de4d4a3d6a5'));

// get specific invoice
#echo '<pre>'.print_r($lexware->get_invoice('7f0f0f7f-dd61-4bf7-a9f7-a67b0530c7e9'), true).'</pre>';

// echo specific invoice number
#$invoice = $lexware->get_invoice('350d7ea4-63f9-44fb-a404-b4de167b4a8e');
#echo $invoice->voucherNumber;

// download invoice pdf
#$lexware->get_invoice_pdf('7f0f0f7f-dd61-4bf7-a9f7-a67b0530c7e9', 'test.pdf'), true);

// create draft invoice
/*
print_r($lexware->create_invoice(array(
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
		'shippingDate' => date(DATE_RFC3339_EXTENDED),
		'shippingType' => 'delivery',
	),
	'paymentConditions' => array(
		'paymentTermLabel' => 'Vorkasse',
		'paymentTermDuration' => 1,
	),
), false));
*/