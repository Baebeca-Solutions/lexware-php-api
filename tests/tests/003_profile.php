<?php
test_start('test profile endpoint');
try {
	$request = $lexware->get_profile();
	if (!empty($request->organizationId)) {
		test_finished(true);
	} else {
		test_finished(false);
	}
}
catch (\Baebeca\LexwareException $e) {
	test($e->getMessage());
	test(print_r($e->getError(), true));
	test_finished(false);
}