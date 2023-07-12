<?php
test_start('search for test contact');
try {
    $request = $lexoffice->search_contact(array(
        'email' => 'klaus@hansbernd.de',
        'customer' => true,
    ));

    if (count($request->content) === 1) {
        test('found ' . count($request->content) . ' contacts');
        test_finished(true);
    } elseif (count($request->content) > 1) {
        test('found '.count($request->content).' contacts');
        test_finished(false);
    } else {
        test('not found, create contact for test');
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
                    'klaus@hansbernd.de'
                ),
            ),
            'note' => '',
        ));

        test_finished(true);
    }

}
catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}

test_start('search for email with contains "klaus@hans"');
try {
    $request = $lexoffice->search_contact(array(
       'email' => 'klaus@hans',
        'customer' => true,
    ));

    if (count($request->content) === 1) {
        test('found '.count($request->content).' contacts - '.$request->content[0]->emailAddresses->business[0]);
        test_finished(true);
    } else {
        test('found '.count($request->content).' contacts - '.print_r($request->content, true));
        test_finished(false);
    }

}
catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}

test_start('search for email with wildcards "k___s@hansbernd.de"');
try {
    $request = $lexoffice->search_contact(array(
        'email' => 'k___s@hansbernd.de',
        'customer' => true,
    ));

    if (count($request->content) === 0) {
        test_finished(true);
    } else {
        test('found '.count($request->content).' contacts - '.print_r($request->content, true));
        test_finished(false);
    }

}
catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}

/*
test_start('search for email with contains "k_*s@hansbernd.de"');
try {
    $request = $lexoffice->search_contact(array(
        'email' => 'k_*s@hansbernd.de',
        'customer' => true,
    ));

    if (count($request->content) === 1) {
        test('found '.count($request->content).' contacts - '.$request->content[0]->emailAddresses->business[0]);
        test_finished(true);
    } else {
        test('found '.count($request->content).' contacts - '.print_r($request->content, true));
        test_finished(false);
    }

} catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}
*/

test_start('search for email with wildcards "kl__s@hansbernd.de"');
try {
    $request = $lexoffice->search_contact(array(
        'email' => 'kl__s@hansbernd.de',
        'customer' => true,
    ));

    if (count($request->content) === 0) {
        test_finished(true);
    } else {
        test('found '.count($request->content).' contacts - '.print_r($request->content, true));
        test_finished(false);
    }

} catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}

test_start('search for email with wildcards "%_s@hansbernd.de"');
try {
    $request = $lexoffice->search_contact(array(
        'email' => '%_s@hansbernd.de',
        'customer' => true,
    ));

    if (count($request->content) === 0) {
        test_finished(true);
    } else {
        test('found '.count($request->content).' contacts - '.print_r($request->content, true));
        test_finished(false);
    }

} catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}


test_start('search for email with wildcards "%@hansbernd.de"');
try {
    $request = $lexoffice->search_contact(array(
        'email' => '%s@hansbernd.de',
        'customer' => true,
    ));

    if (count($request->content) === 0) {
        test_finished(true);
    } else {
        test('found '.count($request->content).' contacts - '.print_r($request->content, true));
        test_finished(false);
    }

} catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}

test_start('search for email with wildcards "klaus@hansbernd.__"');
try {
    $request = $lexoffice->search_contact(array(
        'email' => 'klaus@hansbernd.__',
        'customer' => true,
    ));

    if (count($request->content) === 0) {
        test_finished(true);
    } else {
        test('found '.count($request->content).' contacts - '.print_r($request->content, true));
        test_finished(false);
    }

} catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}
test_start('search for email with wildcards "klaus@hansber%"');
try {
    $request = $lexoffice->search_contact(array(
        'email' => 'klaus@hansber%',
        'customer' => true,
    ));

    if (count($request->content) === 0) {
        test_finished(true);
    } else {
        test('found '.count($request->content).' contacts - '.print_r($request->content, true));
        test_finished(false);
    }

} catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}
