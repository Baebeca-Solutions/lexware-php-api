<?php

test_start('create contact - private person');
try {
	$request = $lexoffice->create_contact(array(
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

test_start('rename previous - private person');
try {
    $contact = $lexoffice->get_contact($request->id);

    // change name
    $contact->person->firstName = 'John changed';
    $contact->person->lastName = 'John changed';

    $request = $lexoffice->update_contact($request->id, $contact);

    if ($request->id) {
        test('contact changed - id: '.$request->id);

        $contact_new = $lexoffice->get_contact($request->id);

        if (
            $contact->person->firstName === $contact_new->person->firstName &&
            $contact->person->lastName === $contact_new->person->lastName
        ) {
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
test_start('create contact - company');
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

test_start('search a contact');
try {
	$request = $lexoffice->search_contact(array(
		'email' => '',
		'name' => $random_contact_name,
		'number' => '',
		'customer' => '', // bool
		'venodr' => '', // bool
	));

	if (count($request->content) == 1) {
		test('found '.count($request->content).' contacts');
		test_finished(true);
	} else {
		test_finished(false);
	}

} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}

test_start('create contact and update billing address');
try {
    $request = $lexoffice->create_contact(array(
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

        try {
            $request = $lexoffice->update_contact($request->id, array(
                'version' => 1,
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
                            'street' => 'Genklerhardt 6 Updated',
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
            test('contact updated - id: '.$request->id);
        } catch(lexoffice_exception $e) {
            test($e->getMessage());
            test(print_r($e->get_error(), true));
            test_finished(false);
        }

        test_finished(true);
    } else {
        test_finished(false);
    }

} catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}

$random_contact_name = 'contact_'.rand(11111111, 999999999999).' (AG)';
test_start('create contact with speacial chars - company');
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

test_start('search a contact with special chars - not encoded');
try {
    $request = $lexoffice->search_contact(array(
        'email' => '',
        'name' => $random_contact_name,
        'number' => '',
        'customer' => '', // bool
        'venodr' => '', // bool
    ));

    if (count($request->content) == 1) {
        test('found '.count($request->content).' contacts');
        test_finished(true);
    } else {
        test_finished(false);
    }

} catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}

test_start('search a contact with special chars - already encoded');
try {
    $request = $lexoffice->search_contact(array(
        'email' => '',
        'name' => rawurlencode($random_contact_name),
        'number' => '',
        'customer' => '', // bool
        'venodr' => '', // bool
    ));

    if (count($request->content) == 1) {
        test('found '.count($request->content).' contacts');
        test_finished(true);
    } else {
        test_finished(false);
    }

} catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}


test_start('try invalid search for a contact - less then 3 chars');
try {
	$request = $lexoffice->search_contact(array(
		'email' => '',
		'name' => 'Jo',
		'number' => '',
		'customer' => '', // bool
		'venodr' => '', // bool
	));
	test_finished(false);

} catch(lexoffice_exception $e) {
	if ($e->getMessage() == 'lexoffice-php-api: search pattern must have least 3 characters') {
		test_finished(true);
	} else {
		test_finished(false);
	}
}

test_start('try invalid search for a contact - no filters');
try {
	$request = $lexoffice->search_contact(array(
		'email' => '',
		'name' => '',
		'number' => '',
		'customer' => '',
		'venodr' => '',
	));
	test_finished(false);

} catch(lexoffice_exception $e) {
	if ($e->getMessage() == 'lexoffice-php-api: no valid filter for searching contacts') {
		test_finished(true);
	} else {
		test_finished(false);
	}
}

test_start('get all contacts');
try {
	$request = $lexoffice->get_contacts_all();
    if (count($request) > 250) test_finished(true);
    test_finished(true);

} catch(lexoffice_exception $e) {
	if ($e->getMessage() == 'lexoffice-php-api: no valid filter for searching contacts') {
		test_finished(false);
	} else {
		test_finished(false);
	}
}

test_start('create contact - norway private person');
try {
    $request = $lexoffice->create_contact(array(
        'version' => 0,
        'roles' => array(
            'customer' => array(
                'number' => '',
            ),
        ),
        'person' => array(
            'salutation' => '',
            'firstName' => 'Håvard',
            'lastName' => 'Doe',
        ),
        'addresses' => array(
            'billing' => array(
                array(
                    'street' => 'Genklerhardt 6',
                    'zip' => '51647',
                    'city' => 'BOLSØYA',
                    'countryCode' => 'NO',
                ),
            ),
        ),
        'emailAddresses' => array(
            'business' => array(
                'support@baebeca.de'
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

test_start('create contact with multiple phone numbers in on eattribute - private person');
try {
    $request = $lexoffice->create_contact(array(
        'version' => 0,
        'roles' => array(
            'customer' => array(
                'number' => '',
            ),
        ),
        'person' => array(
            'salutation' => 'Herr',
            'firstName' => 'John - phone numbers',
            'lastName' => 'Doe',
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
                '+49 1735868143 oder +491721486210'
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
