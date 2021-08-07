<?php
$tries = 500;
test_start('rate limit check - '.$tries.' tries');
for ($i = 0; $i <= $tries; $i++) {
    try {
        $request = $lexoffice->search_contact(['name' => 'ratelimit_'.rand(0, 99999999)]);
        print_r($request);
    } catch (lexoffice_exception $e) {
        if ($e->getMessage() == 'lexoffice-php-api: Endpoint exceeds the limit of throttling. This request should be called again at a later time') {
            print_r($e->get_error());
            test_finished(false);
        } else {
            print_r($e->get_error());
            test_finished(false);
        }
    }
    echo $i. "done\r\n";
    if ($i == $tries) test_finished(false);
}