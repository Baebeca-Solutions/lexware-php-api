<?php
test_start('rate limit check with disabled repeat');
$lexoffice->configure_rate_limit(false);
$success = false;
for ($i = 0; $i <= 20; $i++) {
    try {
        $request = $lexoffice->search_contact(['name' => 'ratelimit_'.rand(0, 99999999)]);
        test('request ...');
    } catch (lexoffice_exception $e) {
        if ($e->getMessage() == 'lexoffice-php-api: Rate limit exceeded') {
            $success = true;
            break;
        }
    }
}
test($i.' request needed');
test_finished($success);

test_start('rate limit check with enabled repeat');
$lexoffice->configure_rate_limit();
$success = true;
for ($i = 0; $i <= 20; $i++) {
    try {
        $request = $lexoffice->search_contact(['name' => 'ratelimit_'.rand(0, 99999999)]);
        test('request ...');
    } catch (lexoffice_exception $e) {
        if ($e->getMessage() == 'lexoffice-php-api: Rate limit exceeded') {
            $success = false;
            break;
        }
    }
}
test($i.' request needed');
test_finished($success);

test_start('rate limit check with enabled repeat and callable');
$lexoffice->configure_rate_limit_callable(function($state){test('callback: '.(string) $state);});
$success = true;
for ($i = 0; $i <= 20; $i++) {
    try {
        $request = $lexoffice->search_contact(['name' => 'ratelimit_'.rand(0, 99999999)]);
        test('request ...');
    } catch (lexoffice_exception $e) {
        if ($e->getMessage() == 'lexoffice-php-api: Rate limit exceeded') {
            $success = false;
            break;
        }
    }
}
test($i.' request needed');
test_finished($success);
$lexoffice->configure_rate_limit_callable();

test_start('rate limit check with disabled repeat and callable');
$lexoffice->configure_rate_limit(false);
$lexoffice->configure_rate_limit_callable(function($state){test('callback: '.(string) $state);});
$success = false;
for ($i = 0; $i <= 20; $i++) {
    try {
        $request = $lexoffice->search_contact(['name' => 'ratelimit_'.rand(0, 99999999)]);
        test('request ...');
    } catch (lexoffice_exception $e) {
        if ($e->getMessage() == 'lexoffice-php-api: Rate limit exceeded') {
            $success = true;
            break;
        }
    }
}
test($i.' request needed');
test_finished($success);
$lexoffice->configure_rate_limit();
$lexoffice->configure_rate_limit_callable();
