<?php

test_start('Validate vat_id - Austria ');
if ($lexware->valid_vat_id('ATU99999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id - incorrect id - Austria ');
if (!$lexware->valid_vat_id('ATU999999997')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Belgium 1');
if ($lexware->valid_vat_id('BE0999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Belgium 2');
if ($lexware->valid_vat_id('BE9999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Bulgaria 1');
if ($lexware->valid_vat_id('BG999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}


test_start('Validate vat_id -  Bulgaria 2');
if ($lexware->valid_vat_id('BG9999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}


test_start('Validate vat_id -  Cyprus');
if ($lexware->valid_vat_id('CY99999999L')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id - incorrect id - Cyprus');
if (!$lexware->valid_vat_id('CY9999999L')) {
    test_finished(true);
} else {
    test_finished(false);
}


test_start('Validate vat_id -  Czech Republic 1');
if ($lexware->valid_vat_id('CZ99999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Czech Republic 2');
if ($lexware->valid_vat_id('CZ999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Czech Republic 3');
if ($lexware->valid_vat_id('CZ9999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Germany');
if ($lexware->valid_vat_id('DE999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Denmark');
if ($lexware->valid_vat_id('DK99999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Estonia');
if ($lexware->valid_vat_id('EE999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Greece');
if ($lexware->valid_vat_id('EL999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Spain');
if ($lexware->valid_vat_id('ESX9999999X')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Finland');
if ($lexware->valid_vat_id('FI99999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  France');
if ($lexware->valid_vat_id('FRXX999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  United Kingdom 1');
if ($lexware->valid_vat_id('GB999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  United Kingdom 2');
if ($lexware->valid_vat_id('GB999999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  United Kingdom 3');
if ($lexware->valid_vat_id('GBGD999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  United Kingdom 4');
if ($lexware->valid_vat_id('GBHA999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Croatia');
if ($lexware->valid_vat_id('HR99999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Hungary');
if ($lexware->valid_vat_id('HU99999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Ireland 1');
if ($lexware->valid_vat_id('IE9S99999L')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Ireland 2');
if ($lexware->valid_vat_id('IE9999999LI')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Italy');
if ($lexware->valid_vat_id('IT99999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Lithuania 1');
if ($lexware->valid_vat_id('LT999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Lithuania 2');
if ($lexware->valid_vat_id('LT999999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Luxemburg');
if ($lexware->valid_vat_id('LU99999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Latvia');
if ($lexware->valid_vat_id('LV99999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Malta');
if ($lexware->valid_vat_id('MT99999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Netherlends');
if ($lexware->valid_vat_id('NLXXXXXXXXXX99')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Poland');
if ($lexware->valid_vat_id('PL9999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Portugal');
if ($lexware->valid_vat_id('PT999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Romania');
if ($lexware->valid_vat_id('RO999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Sweden');
if ($lexware->valid_vat_id('SE999999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Slovenia');
if ($lexware->valid_vat_id('SI99999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id - incorrect id -  Slovenia');
if (!$lexware->valid_vat_id(' ')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id -  Slovakia');
if ($lexware->valid_vat_id('SK9999999999')) {
    test_finished(true);
} else {
    test_finished(false);
}

test_start('Validate vat_id - extra spaces -  Slovakia');
if ($lexware->valid_vat_id(' SK9999999999 ')) {
    test_finished(true);
} else {
    test_finished(false);
}