<?php

// booked xml from an X-Rechnung from \files\XRechnung-sample.xml
$id = 'cbf94710-5ae2-407c-a45e-060e4a6a613a';

test_start('download voucher xml from an X-Rechnung');
try {
    @mkdir(__DIR__.'/tmp');
    $request = $lexoffice->get_voucher_files($id, __DIR__.'/tmp/XRechnung-sample');
    $payload_a = file_get_contents(__DIR__.'/files/XRechnung-sample.xml');
    $payload_b = file_get_contents(__DIR__.'/tmp/XRechnung-sample_1.xml');

    if ($payload_a === $payload_a && file_exists(__DIR__.'/tmp/XRechnung-sample_1.pdf')) {
        test_finished(true);
    }
    else {
        test_finished($payload_b);
    }
}
catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}