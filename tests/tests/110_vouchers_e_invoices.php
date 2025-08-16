<?php
test_start('download voucher xml from an X-Rechnung: '.$xRechnungSampleId);
try {
    $request = $lexware->get_voucher_files($xRechnungSampleId, __DIR__.'/tmp/XRechnung-sample');
    $payload_a = file_get_contents(__DIR__.'/files/XRechnung-sample.xml');
    $payload_b = file_get_contents(__DIR__.'/tmp/XRechnung-sample_1.xml');

    if ($payload_a === $payload_a && file_exists(__DIR__.'/tmp/XRechnung-sample_1.pdf')) {
        unlink(__DIR__.'\tmp\XRechnung-sample_1.xml');
        unlink(__DIR__.'\tmp\XRechnung-sample_1.pdf');
        test_finished(true);
    } else {
        test_finished($payload_b);
    }
}
catch (\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}