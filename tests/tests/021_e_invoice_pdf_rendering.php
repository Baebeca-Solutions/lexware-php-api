<?php

@unlink(__DIR__.'/tmp/021_invoice_final.pdf');
@unlink(__DIR__.'/tmp/021_invoice_final.pdf.xml');
@unlink(__DIR__.'/tmp/021_invoice_draft.pdf');
@unlink(__DIR__.'/tmp/021_invoice_draft.pdf.xml');

$random_contact_name = 'contact'.rand(11111111, 999999999999);
test_start('create contact');
$contact = '';
try {
    $request = $lexoffice->create_contact(array(
        'version' => 0,
        'roles' => array(
            'customer' => array(
                'number' => '',
            ),
        ),
        'company' => array(
            'name' => $random_contact_name,
            'street' => 'Brink 1',
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
                    'street' => 'Brink 1',
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
        'xRechnung' => [
            'buyerReference' => '04011000-1234512345-35',
            'vendorNumberAtCustomer' => '70123456',
        ],
        'note' => '',
    ));

    if ($request->id) {
        test('contact created - id: '.$request->id);
        $contact = $request->id;
        test_finished(true);
    } else {
        test_finished(false);
    }

}
catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}

test_start('create final invoice and download non rendered version');
try {
	$request = $lexoffice->create_invoice([
		'voucherDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
		'introduction' => 'Einleitungstext',
		'remark' => "Fußzeile\r\nMehrzeilig",
		'address' => [
			'contactId' => $contact,
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
        'xRechnung' => [
            'buyerReference' => '04011000-1234512345-35',
        ]
	], true);

	if ($request->id) {
		test('x-invoice created - id: '.$request->id);
        test('download pdf and xml instantly without rendering');
        $lexoffice->get_pdf('invoices', $request->id, __DIR__ . '/tmp/021_invoice_final.pdf');

        if (
            is_file(__DIR__.'/tmp/021_invoice_final.pdf') &&
            is_file(__DIR__.'/tmp/021_invoice_final.pdf.xml')
        ) {
            test_finished(true);
        }
        else {
            test_finished(false);
        }
	} else {
		test_finished(false);
	}
} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}

test_start('create draft invoice and download non rendered version');
try {
    $request = $lexoffice->create_invoice([
        'voucherDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
        'introduction' => 'Einleitungstext',
        'remark' => "Fußzeile\r\nMehrzeilig",
        'address' => [
            'contactId' => $contact,
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
        'xRechnung' => [
            'buyerReference' => '04011000-1234512345-35',
        ]
    ], false);

    if ($request->id) {
        test('x-invoice created - id: '.$request->id);
        test('download pdf and xml instantly without rendering');
        $request = $lexoffice->get_pdf('invoices', $request->id, __DIR__ . '/tmp/021_invoice_draft.pdf');
        if (
            $request === false // at the moment no pdfs for drafts
            #is_file(__DIR__.'/tmp/021_invoice_draft.pdf') &&
            #is_file(__DIR__.'/tmp/021_invoice_draft.pdf.xml')
        ) {
            test_finished(true);
        }
        else {
            test_finished(false);
        }
    } else {
        test_finished(false);
    }
} catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}

@unlink(__DIR__.'/tmp/021_invoice_final.pdf');
@unlink(__DIR__.'/tmp/021_invoice_final.pdf.xml');
@unlink(__DIR__.'/tmp/021_invoice_draft.pdf');
@unlink(__DIR__.'/tmp/021_invoice_draft.pdf.xml');