<?php
$lexoffice->test_set_profile('net', false, 'DESTINATION');

test_start('check voucher booking id - germany sell after oss');
try {
    $request = $lexoffice->get_needed_voucher_booking_id(0, 'de', strtotime('2021-07-05'), false, true, true);
    test_finished($request === '8f8664a8-fd86-11e1-a21f-0800200c9a66');
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
    test_finished($request === '7ecea006-844c-4c98-a02d-aa3142640dd5');
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
    test_finished($request === '7ecea006-844c-4c98-a02d-aa3142640dd5');
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
    test_finished(false);
}
catch (lexoffice_exception $e) {
    test_finished($e->getMessage() === 'lexoffice-php-api: unknown booking scenario, world service with taxes. cannot decide correct booking category');
}

$lexoffice->test_clear_profile();