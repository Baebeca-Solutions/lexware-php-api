<?php

// disabled in default test, only used for special tests
$amount_k = 10;
$create_tons_of_customers = false;

if ($create_tons_of_customers) {
    for ($i = 1; $i <= $amount_k*1000; $i++) {
        $random_contact_name = 'contact_'.rand(11111111, 999999999999);
        test_start('create contact ('.$i.' / '.($amount_k*1000).') - company');
        try {
            $request = $lexoffice->create_contact(array(
                'version' => 0,
                'roles' => array(
                    'customer' => array(
                        'number' => '',
                    ),
                ),
                'company' => array(
                    // use random name, use it in later search check
                    'name' => $random_contact_name,
                    'street' => 'Genklerhardt 6',
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
                            'street' => 'Genklerhardt 6',
                            'zip' => '51647',
                            'city' => 'Gummersbach',
                            'countryCode' => 'DE',
                        ),
                    ),
                    'shipping' => array(
                        array(
                            'street' => 'Genklerhardt 6',
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
    }
}
