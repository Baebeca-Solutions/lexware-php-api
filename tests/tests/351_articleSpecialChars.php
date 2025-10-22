<?php

$title = 'article - <h1>"Bernd & Peter"</h1> - '.rand(11111111, 999999999999);
test_start('Create Article: '.$title);
try {
    $request = $lexware->create_article([
        'title' => $title,
        'description' => '<h1>"Bernd & Peter"</h1>',
        'type' => 'PRODUCT',
        'unitName' => '<h1>"Bernd & Peter"</h1>',
        'price' => [
            'leadingPrice' => 'NET',
            'netPrice' => 44.99,
            'taxRate' => $taxrate_19,
        ],
    ]);
    if ($request->id) {
        $contact = $lexware->get_article($request->id);
        if (
            $contact->title === $title &&
            $contact->description === '<h1>"Bernd & Peter"</h1>' &&
            $contact->unitName === '<h1>"Bernd & Peter"</h1>'
        ) {
            test_finished(true);
        }
        else {
            test_finished(false);
        }
    } else {
        test_finished(false);
    }
}
catch(\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}
