<?php

// to prevent execute unknown callbacks, you should setup your "organizationId" (you see it if you check existing events)
$company_id = '';

$data = file_get_contents('php://input');
$data = json_decode($data);

// todo Verify Authenticity with X-Lxo-Signature
if (!$company_id || $data->organizationId == $company_id) {

	switch ($data->eventType) {
		case 'invoice.status.changed':
			// do some stuff
			break;

		case 'contact.created':
			// do some stuff
			break;

		case 'contact.changed':
			// do some stuff
			break;

		case 'contact.deleted':
			// do some stuff
			break;

		case 'token.revoked':
			// do some stuff
			break;
	}

}