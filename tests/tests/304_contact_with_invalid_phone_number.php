<?php
$update_id = 0;
$random_contact_name = 'contact_'.rand(11111111, 999999999999);
test_start('create contact with contact persons phone number (validation check)');
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
					'emailAddress' =>'',
        			'phoneNumber' => 'Sun 123456789, 987654321',
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
				'support@baebeca.de'
			),
		),
		'phoneNumbers' => array(
			'business' => array(
				'34535345 12121221 8888888888'
			),
		),
		'note' => '',
	));

	if ($request->id) {
		test('contact created - id: '.$request->id);
        $update_id = $request->id;
        $contact = $lexoffice->get_contact($request->id);
        if ($contact->company->contactPersons[0]->phoneNumber === '123456789') test_finished(true);
        else test_finished(false);
	} else {
		test_finished(false);
	}

} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}

test_start('update phone number with letters and invalid size(>30) - company');
try {
    $contact = $lexoffice->get_contact($request->id);
    $contact->phoneNumbers->office[0] = "Manager Julius Cesar 034247632477mistake";
    $contact->phoneNumbers->business[0] = "Crazy 098934343444544654689893844345345436546456 Frog";
    $request = $lexoffice->update_contact($request->id, $contact);
    if ($request->id) {
        test('contact updated - id: '.$request->id);
        $contact_new = $lexoffice->get_contact($request->id);
        if ($contact_new->phoneNumbers->office[0] === "034247632477" && !isset($contact_new->phoneNumbers->business[0])) {
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

