<?php
test_start('test profile endpoint');
try {
	$request = $lexoffice->get_profile();
	if ($request->organizationId == 'a57b9ac1-d316-482c-afae-140fd86e29e1') {
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch (lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}