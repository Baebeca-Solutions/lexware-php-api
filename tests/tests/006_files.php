<?php

test_start('upload voucher');
try {
	$request = $lexoffice->upload_file(__DIR__.'\files\cat.jpg');

	if ($request->id) {
		test('file id: '.$request->id);
		test_finished(true);
	} else {
		test_finished(false);
	}

} catch(lexoffice_exception $e) {
	test($e->getMessage());
	test(print_r($e->get_error(), true));
	test_finished(false);
}

test_start('upload big voucher');
try {
	$request = $lexoffice->upload_file(__DIR__.'\files\cat_5245kb.jpg');
	test_finished(false);

} catch(lexoffice_exception $e) {
	if ($e->getMessage() == 'lexoffice-php-api: filesize to big') {
		test_finished(true);
	} else {
		test($e->getMessage());
		test(print_r($e->get_error(), true));
		test_finished(false);
	}
}

test_start('upload not existing voucher');
try {
	$request = $lexoffice->upload_file(__DIR__.'\files\1337.jpg');
	test_finished(false);

} catch(lexoffice_exception $e) {
	if ($e->getMessage() == 'lexoffice-php-api: file does not exist') {
		test_finished(true);
	} else {
		test($e->getMessage());
		test(print_r($e->get_error(), true));
		test_finished(false);
	}
}

test_start('upload invalid extension');
try {
	$request = $lexoffice->upload_file(__DIR__.'\files\cat.cat');
	test_finished(false);

} catch(lexoffice_exception $e) {
	if ($e->getMessage() == 'lexoffice-php-api: invalid file extension') {
		test_finished(true);
	} else {
		test($e->getMessage());
		test(print_r($e->get_error(), true));
		test_finished(false);
	}
}

