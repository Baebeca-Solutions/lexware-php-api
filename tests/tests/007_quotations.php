<?php

test_start('get a quoation');
try {
	$request = $lexoffice->get_quotation('8cf7d537-bebe-4b53-bb5e-ced26ef8cfb8');

	if ($request->id) {
		test_finished(true);
	} else {
		test_finished(false);
	}

} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}

