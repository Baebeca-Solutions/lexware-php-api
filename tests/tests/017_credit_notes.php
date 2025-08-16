<?php

test_start('creditnote - 19% UST position with special chars in product');
$creditnoteId = '';
try {
    $request = $lexware->create_creditnote([
        'voucherDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
        'introduction' => 'Einleitungstext',
        'remark' => "Fußzeile\r\nMehrzeilig",
        'address' => [
            'contactId' => CONTACT_ID_COMPANY,
            'name' => 'Frau Jane Doe',
            'street' => 'Str. 1',
            'zip' => '12345',
            'city' => 'Stadt',
            'countryCode' => 'DE',
        ],
        'lineItems' => [
            [
                'type' => 'custom',
                'name' => 'WEBINAR BLUB ABC - Duminică, 01.01.2000 la ora 13.00',
                'description' => 'WEBINAR BLUB ABC - Duminică, 01.01.2000 la ora 13.00',
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
    ], true);

    if ($request->id) {
        test('invoice created - id: '.$request->id);
        $creditnoteId = $request->id;
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch(\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}

test_start('get credit note pdf');
@unlink(__DIR__.'/tmp/017_creditnote.pdf');
if ($creditnoteId) {
    test('download pdf');
    $lexware->get_pdf('credit-notes', $creditnoteId, __DIR__ . '/tmp/017_creditnote.pdf');
    if (is_file(__DIR__.'/tmp/017_creditnote.pdf')) {
        unlink(__DIR__.'/tmp/017_creditnote.pdf');
        test_finished(true);
    }
    else {
        test_finished(false);
    }
} else {
    test_finished(false);
}