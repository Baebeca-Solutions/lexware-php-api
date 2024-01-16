<?php

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
		),
		'emailAddresses' => array(
			'business' => array(
				'support@baebeca.de'
			),
		),
		'phoneNumbers' => array(
			'business' => array(
				''
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