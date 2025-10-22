<?php

$title = 'contact - <h1>"Bernd & Peter"</h1> - '.rand(11111111, 999999999999);
test_start('Create Contact: '.$title);
try {
    $request = $lexware->create_contact(array(
        'version' => 0,
        'roles' => array(
            'customer' => array(
                'number' => '',
            ),
        ),
        'company' => array(
            'name' => $title,
            'contactPersons' => array(
                array(
                    'salutation' => '<h1>"',
                    'firstName' => '<h1>"Bernd & Peter"</h1>',
                    'lastName' => '<h1>"Bernd & Peter"</h1>',
                    'emailAddress' =>'no-reply@baebeca.de',
                    'phoneNumber' => '022619202930',
                )
            ),
        ),
        'addresses' => (object) [
            'billing' => [
                [
                    'street' => '<h1>"Bernd & Peter"</h1>',
                    'zip' => '51647',
                    'city' => '<h1>"Bernd & Peter"</h1>',
                    'countryCode' => 'DE'
                ]
            ]
        ],
        'note' => '<h1>"Bernd & Peter"</h1>'
    ));
    if ($request->id) {
        $contact = $lexware->get_contact($request->id);
        if (
            $contact->company->name === $title &&
            $contact->company->contactPersons[0]->salutation === '<h1>"' &&
            $contact->company->contactPersons[0]->firstName === '<h1>"Bernd & Peter"</h1>' &&
            $contact->company->contactPersons[0]->lastName === '<h1>"Bernd & Peter"</h1>' &&
            $contact->addresses->billing[0]->street === '<h1>"Bernd & Peter"</h1>' &&
            $contact->addresses->billing[0]->city === '<h1>"Bernd & Peter"</h1>' &&
            $contact->note === '<h1>"Bernd & Peter"</h1>'
        ) {
            test_finished(true);
        }
        else {
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
