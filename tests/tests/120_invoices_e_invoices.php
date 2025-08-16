<?php
test_start('download pdf with additional xml from an X-Rechnung');
try {
    $lexware->get_pdf('invoices', INVOICE_ID_XRECHNUNG, __DIR__.'/tmp/120_XRechnung.pdf');
    if (file_exists(__DIR__.'/tmp/120_XRechnung.pdf') && file_exists(__DIR__.'/tmp/120_XRechnung.pdf.xml')) {
        unlink(__DIR__.'\tmp\120_XRechnung.pdf');
        unlink(__DIR__.'\tmp\120_XRechnung.pdf.xml');
        test_finished(true);
    }
    else {
        test_finished(false);
    }
}
catch(\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}

test_start('download pdf without additional xml from an default invoice');
try {
    $lexware->get_pdf('invoices', INVOICE_ID, __DIR__.'/tmp/120_Rechnung.pdf');
    if (file_exists(__DIR__.'/tmp/120_Rechnung.pdf') && !file_exists(__DIR__.'/tmp/120_Rechnung.pdf.xml')) {
        unlink(__DIR__.'\tmp\120_Rechnung.pdf');
        test_finished(true);
    }
    else {
        test_finished(false);
    }
}
catch(\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}