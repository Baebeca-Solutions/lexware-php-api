<?php

$random_contact_name = 'contact_'.rand(11111111, 999999999999);
test_start('Create contact - company');
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
        ),
        'note' => '',
    ));
    if ($request->id) {
        test('contact created - id: '.$request->id);
        test_finished(true);
    } else {
        test_finished(false);
    }

} catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}

test_start('Create invoice (Empty address - returns error)');
try {
    $request_2 = $lexoffice->create_invoice([
        'voucherDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
        'introduction' => 'Einleitungstext',
        'remark' => "Fußzeile\r\nMehrzeilig",
        'address' => [
            'contactId' => $request->id,
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
                    'taxRatePercentage' => 19,
                ],
            ],
        ],
        'totalPrice' => [
            'currency' => 'EUR'
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

} catch(lexoffice_exception $e) {
    if ($e->get_error()['Response']->message == 'Referenced contact needs to have exactly one billing address, but 0 addresses were found.') {
        test_finished(true);
    } else {
        test('We expect that this invoice will fail, because the contact has no address. seems like a lexoffice bug.');
        test('If this bug on lexoffice side is fixed, please revert all the changes from case #215872 in related projects');
        test($e->getMessage());
        test(print_r($e->get_error(), true));
        test_finished(false);
    }
}

// the invoice shows address from request, but invoice is linked to contactId
test_start('Create invoice (Contact without address, but it is specified in create_invoice request)');
try {
    $request_2 = $lexoffice->create_invoice([
        'voucherDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
        'introduction' => 'Einleitungstext',
        'remark' => "Fußzeile\r\nMehrzeilig",
        'address' => [
            'contactId' => $request->id,
            'name' => 'Jane Doe',
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
                    'taxRatePercentage' => 19,
                ],
            ],
        ],
        'totalPrice' => [
            'currency' => 'EUR'
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

    if ($request_2->id) {
        test('invoice created - id: '.$request_2->id);
        test_finished(true);
    } else {
        test_finished(false);
    }
} catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}