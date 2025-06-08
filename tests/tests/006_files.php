<?php

test_start('upload voucher');
try {
	$request = $lexware->upload_file(__DIR__.'\files\cat.jpg');

	if ($request->id) {
		test('file id: '.$request->id);
		test_finished(true);
	} else {
		test_finished(false);
	}

} catch(\Baebeca\LexwareException $e) {
	test($e->getMessage());
	test(print_r($e->getError(), true));
	test_finished(false);
}

test_start('upload big voucher');
try {
	$request = $lexware->upload_file(__DIR__.'\files\cat_5245kb.jpg');
	test_finished(false);

} catch(\Baebeca\LexwareException $e) {
	if ($e->getMessage() == 'LexwareApi: filesize to big') {
		test_finished(true);
	} else {
		test($e->getMessage());
		test(print_r($e->getError(), true));
		test_finished(false);
	}
}

test_start('upload voucher pdf from an X-Rechnung');
try {
    $request = $lexware->upload_file(__DIR__.'\files\XRechnung-sample.pdf');
    if ($request->id) {
        test('file id: '.$request->id);
        test_finished(true);
    } else {
        test_finished(false);
    }

} catch(\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}

test_start('upload voucher xml from an X-Rechnung');
try {
    $request = $lexware->upload_file(__DIR__.'\files\XRechnung-sample.xml');
    if ($request->id) {
        test('file id: '.$request->id);
        test_finished(true);
    } else {
        test_finished(false);
    }

} catch(\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}

test_start('upload not existing voucher');
try {
	$request = $lexware->upload_file(__DIR__.'\files\1337.jpg');
	test_finished(false);

} catch(\Baebeca\LexwareException $e) {
	if ($e->getMessage() == 'LexwareApi: file does not exist') {
		test_finished(true);
	} else {
		test($e->getMessage());
		test(print_r($e->getError(), true));
		test_finished(false);
	}
}

test_start('upload invalid mime type');
try {
	$request = $lexware->upload_file(__DIR__.'\files\libssh2.dll');
	test_finished(false);

} catch(\Baebeca\LexwareException $e) {
	if ($e->getMessage() == 'LexwareApi: invalid mime type') {
		test_finished(true);
	} else {
		test($e->getMessage());
		test(print_r($e->getError(), true));
		test_finished(false);
	}
}

test_start('upload jpg without extension');
try {
    $request = $lexware->upload_file(__DIR__.'\files\cat_without_extension');
    if ($request->id) {
        test('file id: '.$request->id);
        test_finished(true);
    } else {
        test_finished(false);
    }

} catch(\Baebeca\LexwareException $e) {
    test($e->getMessage());
    test(print_r($e->getError(), true));
    test_finished(false);
}

