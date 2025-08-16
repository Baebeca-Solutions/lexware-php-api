<?php
test_start('create final invoice and download');
try {
    @unlink(__DIR__.'/tmp/021_invoice_final.pdf');
    @unlink(__DIR__.'/tmp/021_invoice_final.pdf.xml');
    test('download pdf and xml from '.INVOICE_ID_XRECHNUNG);
    $lexware->get_pdf('invoices', INVOICE_ID_XRECHNUNG, __DIR__ . '/tmp/021_invoice_final.pdf');

    if (
        is_file(__DIR__.'/tmp/021_invoice_final.pdf') &&
        is_file(__DIR__.'/tmp/021_invoice_final.pdf.xml')
    ) {
        unlink(__DIR__.'/tmp/021_invoice_final.pdf');
        unlink(__DIR__.'/tmp/021_invoice_final.pdf.xml');
        test_finished(true);
    }
    else {
        test_finished(false);
    }
} catch(\Baebeca\LexwareException $e) {
	test($e->getMessage());
	test(print_r($e->getError(), true));
	test_finished(false);
}

test_start('create draft invoice and download');
try {
    $request = $lexware->create_invoice([
        'voucherDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
        'introduction' => 'Einleitungstext',
        'remark' => "Fußzeile\r\nMehrzeilig",
        'address' => [
            'contactId' => CONTACT_ID_XRECHNUNG,
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
        @unlink(__DIR__.'/tmp/021_invoice_draft.pdf');
        @unlink(__DIR__.'/tmp/021_invoice_draft.pdf.xml');
        test('x-invoice created - id: '.$request->id);
        test('download pdf and xml');
        $request = $lexware->get_pdf('invoices', $request->id, __DIR__ . '/tmp/021_invoice_draft.pdf');
        if ($request === false) {
            test_finished(true);
        }
        else {
            test_finished(false);
        }
    } else {
        test_finished(false);
    }
} catch(\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}

@unlink(__DIR__.'/tmp/021_invoice_final.pdf');
@unlink(__DIR__.'/tmp/021_invoice_final.pdf.xml');
@unlink(__DIR__.'/tmp/021_invoice_draft.pdf');
@unlink(__DIR__.'/tmp/021_invoice_draft.pdf.xml');