<?php
// copy this file to "_local_settings.php"

// https://app.lexware-sandbox.de/
$sandbox = true;

// regular test account - required configurations
// - Netto Preise
// - Ist-Versteuerung
// - OSS - Deutsche Umsatzsteuer
// - E-Rechnung aktiviert
$api_key = ''; //

// 0 if all tests, otherwhise number of test
$run_specific_test = 0;

// show debug output
$debug = true;

// booked SalesInvoice xml from an X-Rechnung from \files\XRechnung-sample.xml
// lexware voucher id
$xRechnungSampleId = '';

// create big amount of customer (n*1000)
// used in test 013_contacts_create_big_amount.php
$create_tons_of_customers = 0;