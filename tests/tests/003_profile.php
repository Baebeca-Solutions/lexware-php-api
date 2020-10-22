<?php
test_start('test profile endpoint');
try {
	$request = $lexoffice->get_profile();
	if (isset($request->organizationId) && !empty($request->organizationId)) {
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch (lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}