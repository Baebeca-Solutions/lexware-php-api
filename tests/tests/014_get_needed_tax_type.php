<?php
test_start('check DE');
try {
	$request = $lexware->get_needed_tax_type('de', '', true, strtotime('2024-03-26'));
    if ($request === 'net') {
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch (\Baebeca\LexwareException $e) {
	test($e->getMessage());
	test_finished(false);
}

test_start('check EU with VAT');
try {
	$request = $lexware->get_needed_tax_type('at', 'AT0123456', false, strtotime('2024-03-26'));
    if ($request === 'intraCommunitySupply') {
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch (\Baebeca\LexwareException $e) {
	test($e->getMessage());
	test_finished(false);
}

test_start('check EU without VAT');
try {
	$request = $lexware->get_needed_tax_type('at', '', false, strtotime('2024-03-26'));
    if ($request === 'net') {
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch (\Baebeca\LexwareException $e) {
	test($e->getMessage());
	test_finished(false);
}

test_start('check World as service');
try {
	$request = $lexware->get_needed_tax_type('US', '', false, strtotime('2024-03-26'));
    if ($request === 'thirdPartyCountryService') {
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch (\Baebeca\LexwareException $e) {
	test($e->getMessage());
	test_finished(false);
}

test_start('check World as physical');
try {
	$request = $lexware->get_needed_tax_type('US', '', true, strtotime('2024-03-26'));
    if ($request === 'thirdPartyCountryDelivery') {
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch (\Baebeca\LexwareException $e) {
	test($e->getMessage());
	test_finished(false);
}

// Drittland-Sondergebiete
test_start('check GL (Grönland) as service - thirdPartyCountryService');
try {
    $request = $lexware->get_needed_tax_type('GL', '', false, strtotime('2024-03-26'));
    test_finished($request === 'thirdPartyCountryService');
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('check GL (Grönland) as physical - thirdPartyCountryDelivery');
try {
    $request = $lexware->get_needed_tax_type('GL', '', true, strtotime('2024-03-26'));
    test_finished($request === 'thirdPartyCountryDelivery');
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('check GP (Guadeloupe) as service - thirdPartyCountryService');
try {
    $request = $lexware->get_needed_tax_type('GP', '', false, strtotime('2024-03-26'));
    test_finished($request === 'thirdPartyCountryService');
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('check AX (Åland-Inseln) as service - thirdPartyCountryService');
try {
    $request = $lexware->get_needed_tax_type('AX', '', false, strtotime('2024-03-26'));
    test_finished($request === 'thirdPartyCountryService');
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('check AD (Andorra) as physical - thirdPartyCountryDelivery');
try {
    $request = $lexware->get_needed_tax_type('AD', '', true, strtotime('2024-03-26'));
    test_finished($request === 'thirdPartyCountryDelivery');
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('check MC (Monaco) with VAT - intraCommunitySupply');
try {
    $request = $lexware->get_needed_tax_type('MC', 'FR12345678901', false, strtotime('2024-03-26'));
    test_finished($request === 'intraCommunitySupply');
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('check MC (Monaco) without VAT - net');
try {
    $request = $lexware->get_needed_tax_type('MC', '', false, strtotime('2024-03-26'));
    test_finished($request === 'net');
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

