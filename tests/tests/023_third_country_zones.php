<?php
// is_excluded_postal_zone() - Direktprüfung PLZ-Sonderzonen
test_start('DE 27498 (Helgoland) → true');
try {
    test_finished($lexware->is_excluded_postal_zone('DE', '27498'));
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('DE 78266 (Büsingen) → true');
try {
    test_finished($lexware->is_excluded_postal_zone('DE', '78266'));
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('ES 35001 (Kanarische Inseln) → true');
try {
    test_finished($lexware->is_excluded_postal_zone('ES', '35001'));
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('ES 38500 (Kanarische Inseln) → true');
try {
    test_finished($lexware->is_excluded_postal_zone('ES', '38500'));
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('ES 51001 (Ceuta) → true');
try {
    test_finished($lexware->is_excluded_postal_zone('ES', '51001'));
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('ES 52001 (Melilla) → true');
try {
    test_finished($lexware->is_excluded_postal_zone('ES', '52001'));
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('IT 23030 (Livigno) → true');
try {
    test_finished($lexware->is_excluded_postal_zone('IT', '23030'));
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('IT 22060 (Campione d\'Italia) → true');
try {
    test_finished($lexware->is_excluded_postal_zone('IT', '22060'));
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('GR 63086 (Berg Athos) → true');
try {
    test_finished($lexware->is_excluded_postal_zone('GR', '63086'));
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('FI 22100 (Åland-Inseln) → true');
try {
    test_finished($lexware->is_excluded_postal_zone('FI', '22100'));
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('DE ohne PLZ → false (kein PLZ = kein Drittgebiet)');
try {
    test_finished(!$lexware->is_excluded_postal_zone('DE', ''));
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('DE 10115 (Berlin) → false (normales DE)');
try {
    test_finished(!$lexware->is_excluded_postal_zone('DE', '10115'));
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('ES 28001 (Madrid) → false (normales ES)');
try {
    test_finished(!$lexware->is_excluded_postal_zone('ES', '28001'));
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('IT 20121 (Mailand) → false (normales IT)');
try {
    test_finished(!$lexware->is_excluded_postal_zone('IT', '20121'));
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

// is_european_member() mit PLZ
test_start('is_european_member DE 27498 (Helgoland) → false');
try {
    test_finished(!$lexware->is_european_member('DE', strtotime('2024-01-01'), '27498'));
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('is_european_member DE 10115 (Berlin) → true');
try {
    test_finished($lexware->is_european_member('DE', strtotime('2024-01-01'), '10115'));
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('is_european_member ES 35001 (Kanarische Inseln) → false');
try {
    test_finished(!$lexware->is_european_member('ES', strtotime('2024-01-01'), '35001'));
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('is_european_member ES 28001 (Madrid) → true');
try {
    test_finished($lexware->is_european_member('ES', strtotime('2024-01-01'), '28001'));
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('is_european_member FI 22100 (Åland-Inseln) → false');
try {
    test_finished(!$lexware->is_european_member('FI', strtotime('2024-01-01'), '22100'));
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('is_european_member FI 00100 (Helsinki) → true');
try {
    test_finished($lexware->is_european_member('FI', strtotime('2024-01-01'), '00100'));
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

// get_needed_tax_type() mit PLZ
test_start('get_needed_tax_type DE 27498 Dienstleistung → thirdPartyCountryService');
try {
    $result = $lexware->get_needed_tax_type('DE', '', false, strtotime('2024-01-01'), '27498');
    test_finished($result === 'thirdPartyCountryService');
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('get_needed_tax_type DE 27498 physisch → thirdPartyCountryDelivery');
try {
    $result = $lexware->get_needed_tax_type('DE', '', true, strtotime('2024-01-01'), '27498');
    test_finished($result === 'thirdPartyCountryDelivery');
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('get_needed_tax_type DE 10115 (Berlin) → net');
try {
    $result = $lexware->get_needed_tax_type('DE', '', false, strtotime('2024-01-01'), '10115');
    test_finished($result === 'net');
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('get_needed_tax_type ES 35001 (Kanaren) Dienstleistung → thirdPartyCountryService');
try {
    $result = $lexware->get_needed_tax_type('ES', '', false, strtotime('2024-01-01'), '35001');
    test_finished($result === 'thirdPartyCountryService');
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('get_needed_tax_type ES 28001 (Madrid) mit VAT → intraCommunitySupply');
try {
    $result = $lexware->get_needed_tax_type('ES', 'ESA12345678', false, strtotime('2024-01-01'), '28001');
    test_finished($result === 'intraCommunitySupply');
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('get_needed_tax_type IT 23030 (Livigno) physisch → thirdPartyCountryDelivery');
try {
    $result = $lexware->get_needed_tax_type('IT', '', true, strtotime('2024-01-01'), '23030');
    test_finished($result === 'thirdPartyCountryDelivery');
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

// get_needed_voucher_booking_id() mit PLZ-Sonderzonen
// Sonderzone: kein OSS nötig, direkt als Drittland behandeln

test_start('get_needed_voucher_booking_id DE 27498 (Helgoland) Warenlieferung privat, 0% → Einnahmen');
try {
    $result = $lexware->get_needed_voucher_booking_id(0, 'DE', strtotime('2024-01-01'), false, false, true, '27498');
    test_finished($result === '8f8664a1-fd86-11e1-a21f-0800200c9a66');
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('get_needed_voucher_booking_id DE 27498 (Helgoland) Warenlieferung B2B, 0% → Ausfuhrlieferung Drittland');
try {
    $result = $lexware->get_needed_voucher_booking_id(0, 'DE', strtotime('2024-01-01'), true, true, true, '27498');
    test_finished($result === '93d24c20-ea84-424e-a731-5e1b78d1e6a9');
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('get_needed_voucher_booking_id DE 27498 (Helgoland) Dienstleistung B2B, 0% → Dienstleistung Drittland');
try {
    $result = $lexware->get_needed_voucher_booking_id(0, 'DE', strtotime('2024-01-01'), true, true, false, '27498');
    test_finished($result === 'ef5b1a6e-f690-4004-9a19-91276348894f');
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('get_needed_voucher_booking_id ES 35001 (Kanarische Inseln) Warenlieferung privat, 0% → Einnahmen (kein OSS)');
try {
    $result = $lexware->get_needed_voucher_booking_id(0, 'ES', strtotime('2024-01-01'), false, false, true, '35001');
    test_finished($result === '8f8664a1-fd86-11e1-a21f-0800200c9a66');
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('get_needed_voucher_booking_id ES 35001 (Kanarische Inseln) Warenlieferung B2B, 0% → Ausfuhrlieferung Drittland');
try {
    $result = $lexware->get_needed_voucher_booking_id(0, 'ES', strtotime('2024-01-01'), true, true, true, '35001');
    test_finished($result === '93d24c20-ea84-424e-a731-5e1b78d1e6a9');
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }

test_start('get_needed_voucher_booking_id ES 28001 (Madrid) ohne PLZ → kein Sondergebiet, normal EU');
try {
    // must not throw, must return an EU-based booking category (not throw missing OSS if OSS configured)
    $result = $lexware->get_needed_voucher_booking_id(0, 'ES', strtotime('2024-01-01'), true, true, true, '28001');
    test_finished($result === '9075a4e3-66de-4795-a016-3889feca0d20'); // Innergemeinschaftliche Lieferung
} catch (\Baebeca\LexwareException $e) { test($e->getMessage()); test_finished(false); }