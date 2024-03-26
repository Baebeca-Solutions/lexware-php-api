<?php
test_start('check DE');
try {
	$request = $lexoffice->get_needed_tax_type('de', '', true, strtotime('2024-03-26'));
    if ($request === 'net') {
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch (lexoffice_exception $e) {
	test($e->getMessage());
	test_finished(false);
}

test_start('check EU with VAT');
try {
	$request = $lexoffice->get_needed_tax_type('at', 'AT0123456', false, strtotime('2024-03-26'));
    if ($request === 'intraCommunitySupply') {
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch (lexoffice_exception $e) {
	test($e->getMessage());
	test_finished(false);
}

test_start('check EU without VAT');
try {
	$request = $lexoffice->get_needed_tax_type('at', '', false, strtotime('2024-03-26'));
    if ($request === 'net') {
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch (lexoffice_exception $e) {
	test($e->getMessage());
	test_finished(false);
}

test_start('check World as service');
try {
	$request = $lexoffice->get_needed_tax_type('US', '', false, strtotime('2024-03-26'));
    if ($request === 'thirdPartyCountryService') {
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch (lexoffice_exception $e) {
	test($e->getMessage());
	test_finished(false);
}

test_start('check World as physical');
try {
	$request = $lexoffice->get_needed_tax_type('US', '', true, strtotime('2024-03-26'));
    if ($request === 'thirdPartyCountryDelivery') {
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch (lexoffice_exception $e) {
	test($e->getMessage());
	test_finished(false);
}

