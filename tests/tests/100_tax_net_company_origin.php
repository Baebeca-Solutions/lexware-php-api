<?php
$lexware->test_set_profile('net', false, 'ORIGIN');

test_start('check voucher booking id - germany sell before oss');
try {
    $request = $lexware->get_needed_voucher_booking_id(0, 'de', strtotime('2021-06-27'), false, true, true);
    test_finished($request === '8f8664a8-fd86-11e1-a21f-0800200c9a66');
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

$lexware->test_clear_profile();