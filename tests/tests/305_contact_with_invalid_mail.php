<?php

$random_contact_name = 'contact_'.rand(11111111, 999999999999);
test_start('Create Contact with invalid E-Mail Address');
try {
    $request = $lexware->create_contact(array(
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
                '..test@email.@company.com',
            ),
        )
    ));
    test_finished(true);
} catch(\Baebeca\LexwareException $e) {
    if ($e->getError()['Response']->IssueList[0]->source === 'emailAddresses[0].emailAddress') {
        test_finished(true);
    }
    else {
        test_finished(true);
    }
}

$random_contact_name = 'contact_'.rand(11111111, 999999999999);
test_start('Create Contact with invalid E-Mail Address(Contact Person)');
try {
    $request = $lexware->create_contact(array(
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
                    'emailAddress' =>'..test@email.@company.com',
                    'phoneNumber' => '022619202930',
                )
            ),
        ),
        'emailAddresses' => array(
            'business' => array(
                ''
            ),
        )
    ));
    test_finished(false);
    test_finished(true);
} catch(\Baebeca\LexwareException $e) {
    if ($e->getError()['Response']->IssueList[0]->source === 'emailAddresses[0].emailAddress') {
        test_finished(true);
    }
    else {
        test_finished(true);
    }
}
