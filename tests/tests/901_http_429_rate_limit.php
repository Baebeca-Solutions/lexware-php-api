<?php
test_start('rate limit check with disabled repeat');
$lexware->configure_rate_limit(false);
$success = false;
for ($i = 0; $i <= 20; $i++) {
    try {
        $request = $lexware->search_contact(['name' => 'ratelimit_'.rand(0, 99999999)]);
        test('request ...');
    } catch (\Baebeca\LexwareException $e) {
        if ($e->getMessage() == 'LexwareApi: Rate limit exceeded') {
            $success = true;
            break;
        }
    }
}
test($i.' request needed');
test_finished($success);

test_start('rate limit check with enabled repeat');
$lexware->configure_rate_limit();
$success = true;
for ($i = 0; $i <= 20; $i++) {
    try {
        $request = $lexware->search_contact(['name' => 'ratelimit_'.rand(0, 99999999)]);
        test('request ...');
    } catch (\Baebeca\LexwareException $e) {
        if ($e->getMessage() == 'LexwareApi: Rate limit exceeded') {
            $success = false;
            break;
        }
    }
}
test($i.' request needed');
test_finished($success);

test_start('rate limit check with enabled repeat and callable');
$lexware->configure_rate_limit_callable(function($state){test('callback: '.(string) $state);});
$success = true;
for ($i = 0; $i <= 20; $i++) {
    try {
        $request = $lexware->search_contact(['name' => 'ratelimit_'.rand(0, 99999999)]);
        test('request ...');
    } catch (\Baebeca\LexwareException $e) {
        if ($e->getMessage() == 'LexwareApi: Rate limit exceeded') {
            $success = false;
            break;
        }
    }
}
test($i.' request needed');
test_finished($success);
$lexware->configure_rate_limit_callable();

test_start('rate limit check with disabled repeat and callable');
$lexware->configure_rate_limit(false);
$lexware->configure_rate_limit_callable(function($state){test('callback: '.(string) $state);});
$success = false;
for ($i = 0; $i <= 20; $i++) {
    try {
        $request = $lexware->search_contact(['name' => 'ratelimit_'.rand(0, 99999999)]);
        test('request ...');
    } catch (\Baebeca\LexwareException $e) {
        if ($e->getMessage() == 'LexwareApi: Rate limit exceeded') {
            $success = true;
            break;
        }
    }
}
test($i.' request needed');
test_finished($success);
$lexware->configure_rate_limit();
$lexware->configure_rate_limit_callable();
