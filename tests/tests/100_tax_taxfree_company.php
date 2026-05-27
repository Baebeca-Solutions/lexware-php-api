<?php
$lexoffice->test_set_profile('vatfree', true, 'ORIGIN');

test_start('check voucher booking id - germany sell before oss - tax_free_company');
try {
    $request = $lexoffice->get_needed_voucher_booking_id(0, 'de', strtotime('2021-06-27'), false, true, true);
    test_finished($request === 'f5c7fee8-f184-4e7a-ab04-8f7e7ad6c207');
}
catch (lexoffice_exception $e) {
    test($e->getMessage());
    test_finished(false);
}

$lexoffice->test_clear_profile();