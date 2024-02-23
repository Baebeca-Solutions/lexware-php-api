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

    $request = $lexoffice->update_contact($request->id, json_decode(json_encode($contact), true));

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
                    'street' => 'Brink 1',
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
                            'street' => 'Brink 1 Updated',
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

$random_contact_name = 'contact'.rand(11111111, 999999999999).' (AG)';
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
            'street' => 'Brink 1',
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

}
catch(lexoffice_exception $e) {
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
        'vendor' => '', // bool
    ));

    if (count($request->content) == 1) {
        test('found '.count($request->content).' contacts');
        test_finished(true);
    } else {
        test_finished(false);
    }

}
catch(lexoffice_exception $e) {
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
        'vendor' => '', // bool
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
		'vendor' => '', // bool
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
		'vendor' => '',
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
                    'street' => 'Brink 1',
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
                'support@baebeca.de'
            ),
        ),
        'phoneNumbers' => array(
            'business' => array(
                '+49 1735860000 oder +491721480000'
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

test_start('create contact with to long phone number - private person');
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
                'support@baebeca.de'
            ),
        ),
        'phoneNumbers' => array(
            'business' => array(
                '01234585252451584265155154156156153'
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

test_start('create contact with invalid empty email - private person');
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
            'business' => [
                ''
            ]
        ),
        'phoneNumbers' => array(
            'business' => array(
                '01234585252451584265155154156156153'
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

test_start('create contact with to much email adresses - private person');
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
            'business' => [
                'test@test.de',
                'test2@test.de',
            ]
        ),
        'phoneNumbers' => array(
            'business' => array(
                '01234585252451584265155154156156153'
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

test_start('create contact with ampersand in name');
$name_with_ampersand = 'johnson & partner'.rand(11111111, 999999999999);
$random_email = 'email'.rand(11111111, 999999999999).'@gmail.com';
try {
    $request = $lexoffice->create_contact(array(
        'version' => 0,
        'roles' => array(
            'customer' => array(
                'number' => '',
            ),
        ),
        'company' => array(
            'name' => $name_with_ampersand,
            'street' => 'Brink 1',
            'zip' => '51647',
            'city' => 'Gummersbach',
            'countryCode' => 'DE',
        ),
        'emailAddresses' => array(
            'business' => array(
                $random_email
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

test_start('search a contact by email');
try {
    $request = $lexoffice->search_contact(array(
        'email' => $random_email,
        'name' => '',
        'number' => '',
        'customer' => true, // bool
        'vendor' => '', // bool
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

test_start('search a contact with ampersand in name');
try {
    $request = $lexoffice->search_contact(array(
        'email' => '',
        'name' => $name_with_ampersand,
        'number' => '',
        'customer' => '', // bool
        'vendor' => '', // bool
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

test_start('search a contact with ampersand in name - html encoded');
try {
    $request = $lexoffice->search_contact(array(
        'email' => '',
        'name' => htmlspecialchars($name_with_ampersand),
        'number' => '',
        'customer' => '', // bool
        'vendor' => '', // bool
    ), true);

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

$name_with_special_chars = "Company <test 'New' version+>".rand(11111111, 999999999999);
test_start('create contact with angle brackets and single quotes in name');
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
            'name' => $name_with_special_chars,
            'street' => 'Brink 1',
            'zip' => '51647',
            'city' => 'Gummersbach',
            'countryCode' => 'DE',
        ),
        'addresses' => array(
            'shipping' => array(
                array(
                    'street' => 'Brink 1',
                    'zip' => '51647',
                    'city' => 'Gummersbach',
                    'countryCode' => 'DE',
                ),
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

test_start('search a contact with angle brackets and single quotes in name');
try {
    $request = $lexoffice->search_contact(array(
        'email' => '',
        'name' => $name_with_special_chars,
        'number' => '',
        'customer' => '', // bool
        'vendor' => '', // bool
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

$email_with_underline = 'email_'.rand(11111111, 999999999999).'@gmail.com';
$contact_name_underline = 'contact_'.rand(11111111, 999999999999);
test_start('create contact with underline in name and email');
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
            'name' => $contact_name_underline,
            'street' => 'Brink 1',
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
                $email_with_underline
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

test_start('search a contact by email with underline');
try {
    $request = $lexoffice->search_contact(array(
        'email' => $email_with_underline,
        'name' => '',
        'number' => '',
        'customer' => true, // bool
        'vendor' => '', // bool
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

test_start('search a contact with underline in name');
try {
    $request = $lexoffice->search_contact(array(
        'email' => '',
        'name' => $contact_name_underline,
        'number' => '',
        'customer' => '', // bool
        'vendor' => '', // bool
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

$name_with_special_chars = 'Company "test version"'.rand(11111111, 999999999999);
test_start('create contact with double quotes in name');
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
            'name' => $name_with_special_chars,
            'street' => 'Brink 1',
            'zip' => '51647',
            'city' => 'Gummersbach',
            'countryCode' => 'DE',
        ),
        'addresses' => array(
            'shipping' => array(
                array(
                    'street' => 'Brink 1',
                    'zip' => '51647',
                    'city' => 'Gummersbach',
                    'countryCode' => 'DE',
                ),
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

test_start('search a contact with double quotes in name');
try {
    $request = $lexoffice->search_contact(array(
        'email' => '',
        'name' => $name_with_special_chars,
        'number' => '',
        'customer' => '', // bool
        'vendor' => '', // bool
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