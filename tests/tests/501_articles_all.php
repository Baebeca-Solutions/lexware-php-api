<?php

test_start('get all articles');
try {
    $request = $lexware->get_articles_all();

    if (count($request)) {
        test('get '.count($request).' articles');
        test_finished(true);
    } else {
        test_finished(false);
    }
} catch(\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}