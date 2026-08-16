<?php
// Kontakttyp-Wechsel zwischen company und person, über alle Rollenkombinationen

$billing_address = [
    'billing' => [
        [
            'street' => 'Brink 1',
            'zip' => '51647',
            'city' => 'Gummersbach',
            'countryCode' => 'DE',
        ],
    ],
];

$person_payload = ['salutation' => 'Herr', 'firstName' => 'Max', 'lastName' => 'Mustermann'];

$role_cases = [
    'Kunde' => ['customer' => ['number' => '']],
    'Lieferant' => ['vendor' => ['number' => '']],
    'Kunde und Lieferant' => ['customer' => ['number' => ''], 'vendor' => ['number' => '']],
];

foreach ($role_cases as $role_title => $roles) {
    test_start('Firma auf Person umstellen - Rolle '.$role_title);
    try {
        $created = $lexware->create_contact([
            'version' => 0,
            'roles' => $roles,
            'company' => ['name' => 'Firma wird Person - '.rand(11111111, 999999999999)],
            'addresses' => $billing_address,
        ]);
        $before = $lexware->get_contact($created->id);
        test('Firma angelegt - id: '.$created->id.' - roles: '.json_encode($before->roles));

        $lexware->update_contact($created->id, [
            'version' => $before->version,
            'roles' => $before->roles,
            'person' => $person_payload,
            'addresses' => $billing_address,
        ]);

        $after = $lexware->get_contact($created->id);
        test('roles nach dem Wechsel: '.json_encode($after->roles));
        if (
            isset($after->person) &&
            !isset($after->company) &&
            ($after->roles->customer->number ?? null) === ($before->roles->customer->number ?? null) &&
            ($after->roles->vendor->number ?? null) === ($before->roles->vendor->number ?? null)
        ) {
            test_finished(true);
        }
        else {
            var_dump($before);
            var_dump($after);
            test_finished(false);
        }
    }
    catch (\Baebeca\LexwareException $e) {
        test($e->getMessage());
        test(print_r($e->getError(), true));
        test_finished(false);
    }

    test_start('Person auf Firma umstellen - Rolle '.$role_title);
    try {
        $created = $lexware->create_contact([
            'version' => 0,
            'roles' => $roles,
            'person' => $person_payload,
            'addresses' => $billing_address,
        ]);
        $before = $lexware->get_contact($created->id);
        test('Person angelegt - id: '.$created->id.' - roles: '.json_encode($before->roles));

        $lexware->update_contact($created->id, [
            'version' => $before->version,
            'roles' => $before->roles,
            'company' => ['name' => 'Person wird Firma - '.rand(11111111, 999999999999)],
            'addresses' => $billing_address,
        ]);

        $after = $lexware->get_contact($created->id);
        test('roles nach dem Wechsel: '.json_encode($after->roles));
        if (
            isset($after->company) &&
            !isset($after->person) &&
            ($after->roles->customer->number ?? null) === ($before->roles->customer->number ?? null) &&
            ($after->roles->vendor->number ?? null) === ($before->roles->vendor->number ?? null)
        ) {
            test_finished(true);
        }
        else {
            var_dump($before);
            var_dump($after);
            test_finished(false);
        }
    }
    catch (\Baebeca\LexwareException $e) {
        test($e->getMessage());
        test(print_r($e->getError(), true));
        test_finished(false);
    }
}

test_start('Steuerfelder einer Firma fallen beim Wechsel auf Person weg');
try {
    $created = $lexware->create_contact([
        'version' => 0,
        'roles' => ['customer' => ['number' => '']],
        'company' => [
            'name' => 'Firma mit Steuerdaten - '.rand(11111111, 999999999999),
            'taxNumber' => '12345/12345',
            'vatRegistrationId' => 'DE123456789',
            'allowTaxFreeInvoices' => false,
        ],
        'addresses' => $billing_address,
    ]);
    test('Firma angelegt - id: '.$created->id);

    $lexware->update_contact($created->id, [
        'version' => $lexware->get_contact($created->id)->version,
        'roles' => ['customer' => ['number' => '']],
        'person' => $person_payload,
        'addresses' => $billing_address,
    ]);

    $contact = $lexware->get_contact($created->id);
    if (
        isset($contact->person) &&
        !isset($contact->company) &&
        !isset($contact->person->taxNumber) &&
        !isset($contact->person->vatRegistrationId) &&
        !isset($contact->person->allowTaxFreeInvoices)
    ) {
        test_finished(true);
    }
    else {
        var_dump($contact);
        test_finished(false);
    }
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}
