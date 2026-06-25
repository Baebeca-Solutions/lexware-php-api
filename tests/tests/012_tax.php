<?php
test_start('check european member');
try {
	$request = $lexware->is_european_member('de', strtotime('2021-06-27'));
    test('check DE');
    if ($request) {
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch (\Baebeca\LexwareException $e) {
	test($e->getMessage());
	test_finished(false);
}

test_start('check european member');
try {
	$request = $lexware->is_european_member('GB', strtotime('2021-06-27'));
    test('check GB');
    if (!$request) {
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch (\Baebeca\LexwareException $e) {
	test($e->getMessage());
	test_finished(false);
}

test_start('check taxrate Österreich');
try {
    $request = $lexware->check_taxrate(floatval(20), 'at', strtotime('2021-07-05'));
    test_finished($request);
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check taxrate DE 19% - ok');
try {
    $request = $lexware->check_taxrate(19, 'DE', strtotime('2020-06-04'));
    test_finished($request);
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check taxrate DE 19% - nok');
try {
    $request = $lexware->check_taxrate(19, 'DE', strtotime('2020-07-02'));
    test_finished(!$request);
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check taxrate DE 19% - ok');
try {
    $request = $lexware->check_taxrate(19, 'DE', strtotime('2021-06-04'));
    test_finished($request);
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check taxrate ES 22% - nok');
try {
    $request = $lexware->check_taxrate(22, 'ES', strtotime('2022-06-04'));
    test_finished(!$request);
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check taxrate DE 7% - ok');
try {
    $request = $lexware->check_taxrate(7, 'DE', strtotime('2020-06-04'));
    test_finished($request);
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check taxrate DE 7% - nok');
try {
    $request = $lexware->check_taxrate(7, 'DE', strtotime('2020-07-02'));
    test_finished(!$request);
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check taxrate DE 7% - ok');
try {
    $request = $lexware->check_taxrate(7, 'DE', strtotime('2021-06-04'));
    test_finished($request);
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check taxrate DE 16% - nok');
try {
    $request = $lexware->check_taxrate(16, 'DE', strtotime('2020-01-01'));
    test_finished(!$request);
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check taxrate DE 16% - ok');
try {
    $request = $lexware->check_taxrate(16, 'DE', strtotime('2020-07-04'));
    test_finished($request);
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check taxrate DE 16% - nok');
try {
    $request = $lexware->check_taxrate(16, 'DE', strtotime('2021-01-01'));
    test_finished(!$request);
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check taxrate DE 5% - nok');
try {
    $request = $lexware->check_taxrate(5, 'DE', strtotime('2020-01-01'));
    test_finished(!$request);
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check taxrate DE 5% - ok');
try {
    $request = $lexware->check_taxrate(5, 'DE', strtotime('2020-07-04'));
    test_finished($request);
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check taxrate DE 5% - nok');
try {
    $request = $lexware->check_taxrate(5, 'DE', strtotime('2021-01-01'));
    test_finished(!$request);
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

// Drittland-Sondergebiete: is_european_member() checks
test_start('check european member GL (Grönland) - false');
try {
    $request = $lexware->is_european_member('GL', strtotime('2024-01-01'));
    test_finished(!$request);
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('check european member FO (Färöer) - false');
try {
    $request = $lexware->is_european_member('FO', strtotime('2024-01-01'));
    test_finished(!$request);
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('check european member AX (Åland-Inseln) - false');
try {
    $request = $lexware->is_european_member('AX', strtotime('2024-01-01'));
    test_finished(!$request);
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('check european member GP (Guadeloupe) - false');
try {
    $request = $lexware->is_european_member('GP', strtotime('2024-01-01'));
    test_finished(!$request);
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('check european member MQ (Martinique) - false');
try {
    $request = $lexware->is_european_member('MQ', strtotime('2024-01-01'));
    test_finished(!$request);
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('check european member RE (Réunion) - false');
try {
    $request = $lexware->is_european_member('RE', strtotime('2024-01-01'));
    test_finished(!$request);
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('check european member GF (Französisch-Guayana) - false');
try {
    $request = $lexware->is_european_member('GF', strtotime('2024-01-01'));
    test_finished(!$request);
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('check european member YT (Mayotte) - false');
try {
    $request = $lexware->is_european_member('YT', strtotime('2024-01-01'));
    test_finished(!$request);
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('check european member AD (Andorra) - false');
try {
    $request = $lexware->is_european_member('AD', strtotime('2024-01-01'));
    test_finished(!$request);
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('check european member MC (Monaco) - true (frz. MwSt-Gebiet)');
try {
    $request = $lexware->is_european_member('MC', strtotime('2024-01-01'));
    test_finished($request);
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('check european member SM (San Marino) - false');
try {
    $request = $lexware->is_european_member('SM', strtotime('2024-01-01'));
    test_finished(!$request);
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('check european member VA (Vatikanstadt) - false');
try {
    $request = $lexware->is_european_member('VA', strtotime('2024-01-01'));
    test_finished(!$request);
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

// Drittland-Sondergebiete: check_taxrate() - 0% muss immer gültig sein
test_start('check taxrate GL 0% - ok');
try {
    $request = $lexware->check_taxrate(0, 'GL', strtotime('2024-01-01'));
    test_finished($request);
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('check taxrate GL 19% - nok (kein DE-Satz für Drittland)');
try {
    $request = $lexware->check_taxrate(19, 'GL', strtotime('2024-01-01'));
    test_finished(!$request);
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('check taxrate GP 8.5% - ok');
try {
    $request = $lexware->check_taxrate(8.5, 'GP', strtotime('2024-01-01'));
    test_finished($request);
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('check taxrate AD 4.5% - ok');
try {
    $request = $lexware->check_taxrate(4.5, 'AD', strtotime('2024-01-01'));
    test_finished($request);
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('check taxrate SM 0% - ok (kein MwSt-System)');
try {
    $request = $lexware->check_taxrate(0, 'SM', strtotime('2024-01-01'));
    test_finished($request);
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }