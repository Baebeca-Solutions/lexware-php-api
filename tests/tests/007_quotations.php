<?php

test_start('create a quoation');
try {
    $request = $lexoffice->create_quotation([
        'voucherDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
        'expirationDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
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
    ], true);

    if ($request->id) {
        test('quote created - id: '.$request->id);
        test_finished(true);
    } else {
        test_finished(false);
    }
} catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}

test_start('get a quoation');
try {
    // $request->id from previous call
    $request = $lexoffice->get_quotation($request->id);

    if ($request->id) {
        test_finished(true);
    } else {
        test_finished(false);
    }

} catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}
