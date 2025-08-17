<?php

test_start('test taxrates unknown country');
try {
    $request = $lexware_OSS_ORIGIN->get_taxrates('ZZ', strtotime('2021-07-05'));
    if ($request['default'] === null) {
        test_finished(true);
    } else {
        var_dump($request);
        test_finished(false);
    }
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('test taxrates country DE');
try {
    $request = $lexware_OSS_ORIGIN->get_taxrates('DE', strtotime('2021-07-05'));
    if (!empty($request) && $request['default'] == 19 && in_array(7, $request['reduced'])) {
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('test taxrates country nl - before oss');
try {
    $request = $lexware_OSS_ORIGIN->get_taxrates('nl', strtotime('2021-06-05'));
    if (!empty($request) && $request['default'] == 19 && in_array(7, $request['reduced'])) {
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('test taxrates country nl - after oss');
try {
    $request = $lexware_OSS_ORIGIN->get_taxrates('nl', strtotime('2021-07-05'));
    if (!empty($request) && $request['default'] == 21 && in_array(9, $request['reduced'])) {
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('test oss settings - DE');
try {
    $request = $lexware_OSS_ORIGIN->is_oss_needed('DE', strtotime('2021-07-05'));
    if ($request === false) {
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('test oss settings - NL');
try {
    $request = $lexware_OSS_ORIGIN->is_oss_needed('NL', strtotime('2021-07-05'));
    if ($request === 'origin') {
        test_finished(true);
    } else {
        var_dump($request);
        test_finished(false);
    }
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('test oss settings - NL - before oss');
try {
    $request = $lexware_OSS_ORIGIN->is_oss_needed('NL', strtotime('2021-06-05'));
    if ($request === false) {
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('test oss settings - ZZ');
try {
    $request = $lexware_OSS_ORIGIN->is_oss_needed('ZZ', strtotime('2021-07-05'));
    if ($request === false) {
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('test oss settings - GB');
try {
    $request = $lexware_OSS_ORIGIN->is_oss_needed('GB', strtotime('2021-07-05'));
    if ($request === false) {
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('test oss voucher category - GB');
try {
    $request = $lexware_OSS_ORIGIN->get_oss_voucher_category('GB', strtotime('2021-07-05'));
    test_finished(false);
}
catch (\Baebeca\LexwareException $e) {
    if ($e->getMessage() === 'LexwareApi: no possible OSS voucher category id') {
        test_finished(true);
    } else {
        test_finished(false);
    }
}

test_start('test oss voucher category - DE');
try {
    $request = $lexware_OSS_ORIGIN->get_oss_voucher_category('DE', strtotime('2021-07-05'));
    test_finished(false);
}
catch (\Baebeca\LexwareException $e) {
    if ($e->getMessage() === 'LexwareApi: no possible OSS voucher category id') {
        test_finished(true);
    } else {
        test_finished(false);
    }
}

test_start('test oss voucher category - NL, physical');
try {
    $request = $lexware_OSS_ORIGIN->get_oss_voucher_category('NL', strtotime('2021-07-05'), 1);
    if ($request === '7c112b66-0565-479c-bc18-5845e080880a') {
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('test oss voucher category - NL, service');
try {
    $request = $lexware_OSS_ORIGIN->get_oss_voucher_category('NL', strtotime('2021-07-05'), 2);
    if ($request === 'd73b880f-c24a-41ea-a862-18d90e1c3d82') {
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check Innergemeinschaftliche Lieferung');
try {
    $request = $lexware_OSS_ORIGIN->get_needed_voucher_booking_id(0, 'PT', strtotime('2021-07-05'), true, true);
    if ($request === '9075a4e3-66de-4795-a016-3889feca0d20') {
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch (\Baebeca\LexwareException $e) {
    test(print_r($e->getError(), true));
    test_finished(false);
}

test_start('check Fernverkauf | vatid but not business, physical');
try {
    $request = $lexware_OSS_ORIGIN->get_needed_voucher_booking_id(23, 'PT', strtotime('2021-07-05'), true, false, true);
    test_finished(false);
}
catch (\Baebeca\LexwareException $e) {
    test_finished($e->getMessage() === 'LexwareApi: invalid OSS taxrate for given country');
}

test_start('check Fernverkauf | vatid but not business, service');
try {
    $request = $lexware_OSS_ORIGIN->get_needed_voucher_booking_id(23, 'PT', strtotime('2021-07-05'), true, false, false);
    test_finished(false);
}
catch (\Baebeca\LexwareException $e) {
    test_finished($e->getMessage() === 'LexwareApi: invalid OSS taxrate for given country');
}