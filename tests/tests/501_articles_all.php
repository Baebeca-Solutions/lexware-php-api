<?php

test_start('get all articles');
try {
    $request = $lexoffice->get_articles_all();

    if (count($request)) {
        test('get '.count($request).' articles');
        test_finished(true);
    } else {
        test_finished(false);
    }
} catch(lexoffice_exception $e) {
    test($e->getMessage());
    test(print_r($e->get_error(), true));
    test_finished(false);
}