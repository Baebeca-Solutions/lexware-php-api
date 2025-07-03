<?php

$random_contact_name = 'contact - BE lower VAT - '.rand(11111111, 999999999999);
test_start('create BE contact with lower VAT ID');
try {
    $contactIn = array(
        'version' => 0,
        'roles' => array(
            'customer' => array(
                'number' => '',
            ),
        ),
        'company' => array(
            'name' => $random_contact_name,
            'allowTaxFreeInvoices' => true,
            'vatRegistrationId' => 'be0896811321'
        ),
        'addresses' => array(
            'billing' => array(
                array(
                    'street' => 'Jan-Baptist Romeostraat 45',
                    'zip' => '8793',
                    'city' => 'Waregem',
                    'countryCode' => 'BE',
                ),
            ),
        ),
        'emailAddresses' => array(
            'business' => array(
                'support@baebeca.de'
            ),
        ),
    );
    $request = $lexware->create_contact($contactIn);

    if ($request->id) {
        test('contact created - id: '.$request->id);
        test('sleep 5');
        sleep(5);

        $contactOut = $lexware->get_contact($request->id);

        if ($contactOut->company->vatRegistrationId === 'BE0896811321') {
            test_finished(true);
        }
        else {
            var_dump($contactIn);
            var_dump($contactOut);
            test_finished(false);
        }
    } else {
        test_finished(false);
    }
}
catch(\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}

$random_contact_name = 'contact - BE upper VAT - '.rand(11111111, 999999999999);
test_start('create BE contact with upper VAT ID');
try {
    $contactIn = array(
        'version' => 0,
        'roles' => array(
            'customer' => array(
                'number' => '',
            ),
        ),
        'company' => array(
            'name' => $random_contact_name,
            'allowTaxFreeInvoices' => true,
            'vatRegistrationId' => 'BE0896811321'
        ),
        'addresses' => array(
            'billing' => array(
                array(
                    'street' => 'Jan-Baptist Romeostraat 45',
                    'zip' => '8793',
                    'city' => 'Waregem',
                    'countryCode' => 'BE',
                ),
            ),
        ),
        'emailAddresses' => array(
            'business' => array(
                'support@baebeca.de'
            ),
        ),
    );
    $request = $lexware->create_contact($contactIn);

    if ($request->id) {
        test('contact created - id: '.$request->id);
        test('sleep 5');
        sleep(5);

        $contactOut = $lexware->get_contact($request->id);

        if ($contactOut->company->vatRegistrationId === 'BE0896811321') {
            test_finished(true);
        }
        else {
            var_dump($contactIn);
            var_dump($contactOut);
            test_finished(false);
        }
    } else {
        test_finished(false);
    }
}
catch(\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}