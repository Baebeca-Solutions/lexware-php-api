<?php
@mkdir(realpath(__DIR__.'\tmp'));

if (!empty($skip_000_init_test)) goto skip_000_init_test;

test_start('create contact - private person');
try {
    $request = $lexware->create_contact(array(
        'version' => 0,
        'roles' => array(
            'customer' => array(
                'number' => '',
            ),
        ),
        'person' => array(
            'salutation' => 'Herr',
            'firstName' => 'John',
            'lastName' => 'Doe',
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
            'shipping' => array(
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
                'no-reply@baebeca.de'
            ),
        ),
        'phoneNumbers' => array(
            'business' => array(
                '022619202930'
            ),
        ),
        'note' => '',
    ));

    if ($request->id) {
        test('CONTACT_ID_PRIVATE: '.$request->id);
        define('CONTACT_ID_PRIVATE', $request->id);
        test_finished(true);
    } else {
        test_finished(false);
    }

}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}

test_start('create contact - company');
try {
    $request = $lexware->create_contact(array(
        'version' => 0,
        'roles' => array(
            'customer' => array(
                'number' => '',
            ),
        ),
        'company' => array(
            'name' => 'John Doe Company',
            'street' => 'Brink 1',
            'zip' => '51647',
            'city' => 'Gummersbach',
            'countryCode' => 'DE',
            'contactPersons' => array(
                array(
                    'salutation' => 'Herr',
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'emailAddress' =>'no-reply@baebeca.de',
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
            'shipping' => array(
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
                'no-reply@baebeca.de'
            ),
        ),
        'phoneNumbers' => array(
            'business' => array(
                '022619202930'
            ),
        ),
        'note' => '',
    ));

    if ($request->id) {
        test('CONTACT_ID_COMPANY: '.$request->id);
        define('CONTACT_ID_COMPANY', $request->id);
        test_finished(true);
    } else {
        test_finished(false);
    }

}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}

test_start('create contact - x-rechnung');
try {
    $request = $lexware->create_contact(array(
        'version' => 0,
        'roles' => array(
            'customer' => array(
                'number' => '',
            ),
        ),
        'company' => array(
            'name' => 'contact - x-rechnung',
            'street' => 'Brink 1',
            'zip' => '51647',
            'city' => 'Gummersbach',
            'countryCode' => 'DE',
            'contactPersons' => array(
                array(
                    'salutation' => 'Herr',
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'emailAddress' =>'no-reply@baebeca.de',
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
                'no-reply@baebeca.de'
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
        test('CONTACT_ID_XRECHNUNG: '.$request->id);
        define('CONTACT_ID_XRECHNUNG', $request->id);
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

test_start('create non e-invoice final');
try {
    $request = $lexware->create_invoice([
        'voucherDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
        'introduction' => 'Einleitungstext',
        'remark' => "Fußzeile\r\nMehrzeilig",
        'address' => [
            #'contactId' => '',
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
    ], true);

    if ($request->id) {
        test('INVOICE_ID_NON_E_INVOICE: '.$request->id);
        define('INVOICE_ID_NON_E_INVOICE', $request->id);
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}

test_start('create invoice final x-rechnung');
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
    ], true);

    if ($request->id) {
        test('INVOICE_ID_XRECHNUNG: '.$request->id);
        define('INVOICE_ID_XRECHNUNG', $request->id);
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

skip_000_init_test: