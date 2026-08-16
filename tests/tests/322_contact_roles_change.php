<?php
// Rollenwechsel eines Kontakts zwischen customer, vendor und beiden

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

$role_states = [
    'Kunde' => ['customer'],
    'Lieferant' => ['vendor'],
    'Kunde und Lieferant' => ['customer', 'vendor'],
];

foreach ($role_states as $from_title => $from_roles) {
    foreach ($role_states as $to_title => $to_roles) {
        if ($from_roles === $to_roles) continue;

        test_start('Rollenwechsel '.$from_title.' auf '.$to_title);
        try {
            $create_roles = [];
            foreach ($from_roles as $role) {
                $create_roles[$role] = ['number' => ''];
            }
            $created = $lexware->create_contact([
                'version' => 0,
                'roles' => $create_roles,
                'company' => ['name' => 'Rollenwechsel - '.rand(11111111, 999999999999)],
                'addresses' => $billing_address,
            ]);
            $before = $lexware->get_contact($created->id);
            test('angelegt - id: '.$created->id.' - roles: '.json_encode($before->roles));

            // keep the number of a role that survives the change, Lexware assigns one for a new role
            $update_roles = [];
            foreach ($to_roles as $role) {
                $update_roles[$role] = ['number' => $before->roles->{$role}->number ?? ''];
            }
            $lexware->update_contact($created->id, [
                'version' => $before->version,
                'roles' => $update_roles,
                'company' => ['name' => 'Rollenwechsel - '.rand(11111111, 999999999999)],
                'addresses' => $billing_address,
            ]);

            $after = $lexware->get_contact($created->id);
            test('roles nach dem Wechsel: '.json_encode($after->roles));

            $expected_roles = $to_roles;
            sort($expected_roles);
            $actual_roles = array_keys((array) $after->roles);
            sort($actual_roles);
            if ($expected_roles === $actual_roles) {
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
}

test_start('Rollennummer bleibt erhalten, wenn sie mitgeschickt wird');
try {
    $created = $lexware->create_contact([
        'version' => 0,
        'roles' => ['customer' => ['number' => '']],
        'company' => ['name' => 'Nummer mitgeschickt - '.rand(11111111, 999999999999)],
        'addresses' => $billing_address,
    ]);
    $before = $lexware->get_contact($created->id);
    test('angelegt - Kundennummer: '.($before->roles->customer->number ?? ''));

    $lexware->update_contact($created->id, [
        'version' => $before->version,
        'roles' => ['customer' => ['number' => $before->roles->customer->number], 'vendor' => ['number' => '']],
        'company' => ['name' => 'Nummer mitgeschickt - '.rand(11111111, 999999999999)],
        'addresses' => $billing_address,
    ]);

    $after = $lexware->get_contact($created->id);
    test('Kundennummer danach: '.($after->roles->customer->number ?? '').' - Lieferantennummer: '.($after->roles->vendor->number ?? ''));
    if (
        ($after->roles->customer->number ?? null) === $before->roles->customer->number &&
        !empty($after->roles->vendor->number)
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

test_start('Leere Rollennummer im Update behält die bestehende Nummer');
try {
    $created = $lexware->create_contact([
        'version' => 0,
        'roles' => ['customer' => ['number' => '']],
        'company' => ['name' => 'Leere Nummer - '.rand(11111111, 999999999999)],
        'addresses' => $billing_address,
    ]);
    $before = $lexware->get_contact($created->id);
    test('angelegt - Kundennummer: '.($before->roles->customer->number ?? ''));

    $lexware->update_contact($created->id, [
        'version' => $before->version,
        'roles' => ['customer' => ['number' => '']],
        'company' => ['name' => 'Leere Nummer - '.rand(11111111, 999999999999)],
        'addresses' => $billing_address,
    ]);

    $after = $lexware->get_contact($created->id);
    test('Kundennummer danach: '.($after->roles->customer->number ?? ''));
    if (($after->roles->customer->number ?? null) === $before->roles->customer->number) {
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

test_start('Entfernte Rolle verliert ihre Nummer endgültig');
try {
    $created = $lexware->create_contact([
        'version' => 0,
        'roles' => ['customer' => ['number' => '']],
        'company' => ['name' => 'Nummer verloren - '.rand(11111111, 999999999999)],
        'addresses' => $billing_address,
    ]);
    $first = $lexware->get_contact($created->id);
    $first_number = $first->roles->customer->number;
    test('angelegt - Kundennummer: '.$first_number);

    $lexware->update_contact($created->id, [
        'version' => $first->version,
        'roles' => ['vendor' => ['number' => '']],
        'company' => ['name' => 'Nummer verloren - '.rand(11111111, 999999999999)],
        'addresses' => $billing_address,
    ]);
    $without_customer = $lexware->get_contact($created->id);
    test('nach dem Entfernen der Kundenrolle: '.json_encode($without_customer->roles));

    $lexware->update_contact($created->id, [
        'version' => $without_customer->version,
        'roles' => ['customer' => ['number' => '']],
        'company' => ['name' => 'Nummer verloren - '.rand(11111111, 999999999999)],
        'addresses' => $billing_address,
    ]);
    $again = $lexware->get_contact($created->id);
    test('Kundennummer nach der Rückkehr: '.($again->roles->customer->number ?? ''));

    if (
        !isset($without_customer->roles->customer) &&
        !empty($again->roles->customer->number) &&
        $again->roles->customer->number !== $first_number
    ) {
        test_finished(true);
    }
    else {
        var_dump($first);
        var_dump($again);
        test_finished(false);
    }
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}

test_start('Eine vergebene Rollennummer lässt sich nicht überschreiben');
try {
    $created = $lexware->create_contact([
        'version' => 0,
        'roles' => ['customer' => ['number' => '']],
        'company' => ['name' => 'Nummer überschreiben - '.rand(11111111, 999999999999)],
        'addresses' => $billing_address,
    ]);
    $before = $lexware->get_contact($created->id);
    $foreign_number = $before->roles->customer->number - 1;
    test('angelegt - Kundennummer: '.$before->roles->customer->number.' - Versuch mit: '.$foreign_number);

    try {
        $lexware->update_contact($created->id, [
            'version' => $before->version,
            'roles' => ['customer' => ['number' => $foreign_number]],
            'company' => ['name' => 'Nummer überschreiben - '.rand(11111111, 999999999999)],
            'addresses' => $billing_address,
        ]);
        test('Update wurde angenommen');
        var_dump($lexware->get_contact($created->id));
        test_finished(false);
    }
    catch (\Baebeca\LexwareException $e) {
        $error = $e->getError();
        test('Update abgelehnt: '.json_encode($error['Response']->IssueList ?? null));
        test_finished(($error['Response']->IssueList[0]->source ?? '') === 'roles.customer.number');
    }
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}
