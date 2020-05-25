<?php

// to prevent execute callbacks for other keys/companies, you can setup your "organizationId" as whitelist
$company_id = '';

$data_webhook = file_get_contents('php://input');
$data = json_decode($data_webhook);

$headers = getallheaders();
if (!isset($headers['X-Lxo-Signature'])) exit('no X-Lxo-Signature is given');

$lexoffice_key_public_api = '-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAtCkZXZqT2zFRmA83KsBC
tQSv9t+AaWiZpRWvzE4wiQEh0aHzNYkT/DlP419pngqoLxnqW9SsgWfKOG3utoEV
z+Lru9odZntW/n/yaRK3f0AcMtuWs/Z1PZ4BbN/RlFYyqlPg0VfuAvZMAa+J9WAl
Fuy0T4Y0EdO0d+EL8BpzSLqj3XLI+YuK4hUhlTOxHmdDtNUkugnT9b9khb+oPDmB
3yxRP6Rmo2qo6waSc+pihCdOiZKzwA4fA1HT8DM54OaNQ4qd9NRf5uECijFlpQu6
vZdW7Z+LZ8HTgGQVSsJdCzUtM1wiWOERrHvd8IpBMvUAvGM/qaJnAmwsEr4J/El4
yCmur8wwYBtSUH0Xue42YysdmSS0pOMMUAAvr70HQMDHagYkErMtu6FAyAmPz8p/
tAqJJpwAhxITbnc5WsmZOu0Ke255nXQ/2Go1TKv+45mSb7RLsvpmhVZ4nFByFt24
vG6IwNReuLhJJL468jrdTpxyZRn0QqkxiQV7jDd9Dp1NVd/W3+n86Aos9LnJa+ut
kN+jOOWmJdJjnQetJlbDVg3ex+XHVmCjiRYggCGPlpXVJwdEUNGzxhPGKjxmeiQT
ahwFA9iHtwBw7yK5VfM/hA+JeF2FXYhTdehfClAQt1YCYXkgUEFxm9idRdBoCY6U
bmFUXQLdi7tZyDor8Rxoq2MCAwEAAQ==
-----END PUBLIC KEY-----';

if (1 === openssl_verify ($data_webhook, base64_decode($headers['X-Lxo-Signature']), $lexoffice_key_public_api, 'RSA-SHA512')) {

	if ($company_id && $data->organizationId == $company_id) exit('invalid organizationId');

	switch ($data->eventType) {
		case 'invoice.status.changed':
			// do some stuff
			break;

		case 'invoice.created':
			// do some stuff
			break;

		case 'invoice.changed':
			// do some stuff
			break;

		case 'invoice.deleted':
			// do some stuff
			break;

		case 'credit-note.created':
			// do some stuff
			break;

		case 'credit-note.changed':
			// do some stuff
			break;

		case 'credit-note.deleted':
			// do some stuff
			break;

		case 'credit-note.status.changed':
			// do some stuff
			break;

		case 'quotation.created':
			// do some stuff
			break;

		case 'quotation.changed':
			// do some stuff
			break;

		case 'quotation.deleted':
			// do some stuff
			break;

		case 'quotation.status.changed':
			// do some stuff
			break;

		case 'order-confirmation.created':
			// do some stuff
			break;

		case 'order-confirmation.changed':
			// do some stuff
			break;

		case 'order-confirmation.deleted':
			// do some stuff
			break;

		case 'order-confirmation.status.changed':
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
} else {
	exit('invalid signature');
}