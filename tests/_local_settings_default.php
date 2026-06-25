<?php
// copy this file to "_local_settings.php"

// PHP binary paths - set to your local PHP installations
// Use $php_binary_8  on branches requiring PHP 8.x  (e.g. master, php-8.4)
// Use $php_binary_74 on branches requiring PHP 7.4   (e.g. php-7.4)
// CLI usage: [binary] tests/test.php [test-number]
$php_binary_8  = ''; // e.g. C:\php-8.5.0-nts-Win32-vs17-x64\php.exe
$php_binary_74 = ''; // e.g. C:\php-7.4.33-nts-Win32-vc15-x64\php.exe

// https://app.lexware-sandbox.de/
$sandbox = true;

// regular test account - required configurations
// - Netto Preise
// - Ist-Versteuerung
// - OSS - Deutsche Umsatzsteuer
// - E-Rechnung aktiviert
$api_key = ''; //

// separate test account - required configurations
// - Netto Preise
// - Ist-Versteuerung
// - OSS - Ziellandsteuer
$api_key_OSS_DESTINATION = ''; //

// 0 if all tests, otherwhise number of test
$skip_000_init_test = 0;
$run_specific_test = 0;

// show debug output
$debug = true;

// booked SalesInvoice xml from an X-Rechnung from \files\XRechnung-sample.xml
// lexware voucher id
$xRechnungSampleId = '';

// create big amount of customer (n*1000)
// used in test 013_contacts_create_big_amount.php
$create_tons_of_customers = 0;