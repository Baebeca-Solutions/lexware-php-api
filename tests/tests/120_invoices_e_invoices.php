<?php

// in lexware created X-Rechnung
$id_xrechnung = '7db0d177-b612-4318-bc73-c390bc04c63a'; // ok
$id_defaultInvoice = 'd914c3eb-b96e-41c9-9b1f-b54c39f6b8e0'; // nok
@mkdir(__DIR__.'\tmp');

test_start('download pdf with additional xml from an X-Rechnung');
try {
    $lexoffice->get_pdf('invoices', $id_xrechnung, __DIR__.'/tmp/120_XRechnung.pdf');
    if (file_exists(__DIR__.'/tmp/120_XRechnung.pdf') && file_exists(__DIR__.'/tmp/120_XRechnung.pdf.xml')) {
        test_finished(true);
    }
    else {
        test_finished(false);
    }
}
catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}

test_start('download pdf without additional xml from an default invoice');
try {
    $lexoffice->get_pdf('invoices', $id_defaultInvoice, __DIR__.'/tmp/120_Rechnung.pdf');
    if (file_exists(__DIR__.'/tmp/120_Rechnung.pdf') && !file_exists(__DIR__.'/tmp/120_Rechnung.pdf.xml')) {
        test_finished(true);
    }
    else {
        test_finished(false);
    }
}
catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}