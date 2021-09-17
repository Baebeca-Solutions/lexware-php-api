<?php

// disabled in default test, only used for special tests

/*
test_start('create 40k vouchers');
$amount_vouchers = 40000;
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
*/