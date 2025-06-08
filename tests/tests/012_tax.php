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