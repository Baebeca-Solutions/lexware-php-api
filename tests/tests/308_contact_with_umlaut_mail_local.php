<?php

$random_contact_name = 'contact_'.rand(11111111, 999999999999);
test_start('Create Contact with strange E-Mail Address');
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
            'countryCode' => 'DE'
        ),
        'emailAddresses' => array(
            'business' => array(
                'häns@klose.de',
            ),
        )
    ));
    if ($request->id) {
        $contact = $lexoffice->get_contact($request->id);
        if (!empty($contact->emailAddresses->business[0])) {
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

$random_contact_name = 'contact_'.rand(11111111, 999999999999);
test_start('Create Contact with invalid E-Mail Address(Contact Person)');
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
                    'emailAddress' =>'häns@klose.de',
                    'phoneNumber' => '022619202930',
                )
            ),
        ),
    ));
    if ($request->id) {
        $contact = $lexoffice->get_contact($request->id);
        if (!empty($contact->company->contactPersons[0]->emailAddress)) {
            test_finished(true);
        }
        else test_finished(false);
    } else {
        test_finished(false);
    }
} catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}