<?php
$lexware->test_set_profile('vatfree', true, 'ORIGIN');

test_start('check voucher booking id - germany sell before oss - tax_free_company');
try {
    $request = $lexware->get_needed_voucher_booking_id(0, 'de', strtotime('2021-06-27'), false, true, true);
    test_finished($request === '7a1efa0e-6283-4cbf-9583-8e88d3ba5960');
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test_finished(false);
}

$lexware->test_clear_profile();