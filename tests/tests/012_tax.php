<?php
test_start('check european member');
try {
	$request = $lexoffice->is_european_member('de', strtotime('2021-06-27'));
    test('check DE');
    if ($request) {
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch (lexoffice_exception $e) {
	test($e->getMessage());
	test_finished(false);
}

test_start('check european member');
try {
	$request = $lexoffice->is_european_member('GB', strtotime('2021-06-27'));
    test('check GB');
    if (!$request) {
		test_finished(true);
	} else {
		test_finished(false);
	}
} catch (lexoffice_exception $e) {
	test($e->getMessage());
	test_finished(false);
}

test_start('check voucher booking id - germany sell before oss');
try {
    $request = $lexoffice->get_needed_voucher_booking_id(0, 'de', strtotime('2021-06-27'), false, true, true);
    test_finished($request === '8f8664a1-fd86-11e1-a21f-0800200c9a66');
}
catch (lexoffice_exception $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check voucher booking id - germany sell after oss');
try {
    $request = $lexoffice->get_needed_voucher_booking_id(0, 'de', strtotime('2021-07-05'), false, true, true);
    test_finished($request === '8f8664a1-fd86-11e1-a21f-0800200c9a66');
}
catch (lexoffice_exception $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check voucher booking id - nl physical good sell - 0% - before oss');
try {
    $request = $lexoffice->get_needed_voucher_booking_id(0, 'nl', strtotime('2021-06-27'), false, true, true);
    test_finished($request === '8f8664a1-fd86-11e1-a21f-0800200c9a66');
}
catch (lexoffice_exception $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check voucher booking id - nl physical good sell - 0% - after oss');
try {
    $request = $lexoffice->get_needed_voucher_booking_id(0, 'nl', strtotime('2021-07-05'), false, true, true);
    test_finished($request === '4ebd965a-7126-416c-9d8c-a5c9366ee473');
}
catch (lexoffice_exception $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check voucher booking id - nl digital good sell - 0% - before oss');
try {
    $request = $lexoffice->get_needed_voucher_booking_id(0, 'nl', strtotime('2021-06-05'),false, true, false);
    test_finished($request === '8f8664a1-fd86-11e1-a21f-0800200c9a66');
}
catch (lexoffice_exception $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check voucher booking id - nl digital good sell - 0% - after oss');
try {
    $request = $lexoffice->get_needed_voucher_booking_id(0, 'nl', strtotime('2021-07-05'),false, true, false);
    test_finished($request === 'efa82f40-fd85-11e1-a21f-0800200c9a66');
}
catch (lexoffice_exception $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check voucher booking id - nl physical good sell - 21% - before oss');
try {
    $request = $lexoffice->get_needed_voucher_booking_id(21, 'nl', strtotime('2021-06-05'), false, false, true);
    test_finished($request === '8f8664a1-fd86-11e1-a21f-0800200c9a66');
}
catch (lexoffice_exception $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check voucher booking id - nl physical good sell - 21% - after oss');
try {
    $request = $lexoffice->get_needed_voucher_booking_id(21, 'nl', strtotime('2021-07-05'), false, false, true);
    test_finished($request === '4ebd965a-7126-416c-9d8c-a5c9366ee473');
}
catch (lexoffice_exception $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check voucher booking id - nl digital good sell - 9% - before oss');
try {
    $request = $lexoffice->get_needed_voucher_booking_id(9, 'nl', strtotime('2021-06-05'), false, false, false);
    test_finished($request === '8f8664a1-fd86-11e1-a21f-0800200c9a66');
}
catch (lexoffice_exception $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check voucher booking id - nl digital good sell - 9% - after oss');
try {
    $request = $lexoffice->get_needed_voucher_booking_id(9, 'nl', strtotime('2021-07-05'), false, false, false);
    test_finished($request === 'efa82f40-fd85-11e1-a21f-0800200c9a66');
}
catch (lexoffice_exception $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check voucher booking id - ch b2b digital good sell');
try {
    $request = $lexoffice->get_needed_voucher_booking_id(0, 'ch', strtotime('2021-07-05'),false, true, false);
    test_finished($request === 'ef5b1a6e-f690-4004-9a19-91276348894f');
}
catch (lexoffice_exception $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check voucher booking id - ch b2c digital good sell');
try {
    $request = $lexoffice->get_needed_voucher_booking_id(19, 'ch', strtotime('2021-07-05'),false, false, false);
    test_finished($request === '8f8664a1-fd86-11e1-a21f-0800200c9a66');
}
catch (lexoffice_exception $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check taxrate Österreich');
try {
    $request = $lexoffice->check_taxrate(floatval(20), 'at', strtotime('2021-07-05'));
    test_finished($request);
}
catch (lexoffice_exception $e) {
    test($e->getMessage());
    test_finished(false);
}
