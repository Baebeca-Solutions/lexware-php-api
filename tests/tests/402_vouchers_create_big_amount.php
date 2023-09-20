<?php

// disabled in default test, only used for special tests
$amount_k = 10;
$upload_vouchers_without_image = false;
$upload_vouchers_with_image = false;

if ($upload_vouchers_without_image) {
    test_start('create '.$amount_k.'k vouchers without image');
    $amount_vouchers = $amount_k*1000;
    for ($i = 0; $i <= $amount_vouchers; $i++) {
        try {
            $day = rand(1, 28);
            if (strlen($day) == 1) $day = '0'.$day;
            $month = rand(1, 12);
            if (strlen($month) == 1) $month = '0'.$month;
            $year = rand(19, 21);

            $request = $lexoffice->create_voucher([
                'version' => 0,
                'type' => 'salesinvoice',
                'voucherNumber' => '6126',
                'voucherDate' => '20'.$year.'-'.$month.'-'.$day,
                'dueDate' => '20'.$year.'-'.$month.'-'.$day,
                'useCollectiveContact' => true,
                'totalGrossAmount' => 175.37,
                'taxType' => "gross",
                'totalTaxAmount' => 28.00,
                'voucherItems' => [
                    [
                        'amount' => 175.37,
                        'taxAmount' => 28.00,
                        'taxRatePercent' => 19,
                        'categoryId' => '8f8664a1-fd86-11e1-a21f-0800200c9a66',
                    ],
                ],
            ]);

            if ($request->id) {
                test('voucher created ('.$i.' / '.$amount_vouchers.') - id: '.$request->id);
                test_finished(true);
            } else {
                test_finished(false);
            }
        } catch(lexoffice_exception $e) {
            test($e->getMessage());
            test(print_r($e->get_error(), true));
            test_finished(false);
        }
    }
}

if ($upload_vouchers_with_image) {
    test_start('create '.$amount_k.'k vouchers with image');
    $amount_vouchers = $amount_k*1000;
    for ($i = 0; $i <= $amount_vouchers; $i++) {
        try {
            $day = rand(1, 28);
            if (strlen($day) == 1) $day = '0'.$day;
            $month = rand(1, 12);
            if (strlen($month) == 1) $month = '0'.$month;
            $year = rand(19, 21);

            $request = $lexoffice->create_voucher([
                'version' => 0,
                'type' => 'salesinvoice',
                'voucherNumber' => '6126',
                'voucherDate' => '20'.$year.'-'.$month.'-'.$day,
                'dueDate' => '20'.$year.'-'.$month.'-'.$day,
                'useCollectiveContact' => true,
                'totalGrossAmount' => 175.37,
                'taxType' => "gross",
                'totalTaxAmount' => 28.00,
                'voucherItems' => [
                    [
                        'amount' => 175.37,
                        'taxAmount' => 28.00,
                        'taxRatePercent' => 19,
                        'categoryId' => '8f8664a1-fd86-11e1-a21f-0800200c9a66',
                    ],
                ],
            ]);

            if ($request->id) {
                test('voucher created ('.$i.' / '.$amount_vouchers.') - id: '.$request->id);

                try {
                    $lexoffice->upload_voucher($request->id, __DIR__.'/files/dummy_2.pdf');
                    test('voucher uploaded');
                } catch (lexoffice_exception $e2) {
                    test($e2->getMessage());
                    test(print_r($e2->get_error(), true));
                    test_finished(false);
                }
            } else {
                test_finished(false);
            }
        } catch(lexoffice_exception $e) {
            test($e->getMessage());
            test(print_r($e->get_error(), true));
            test_finished(false);
        }
    }
}