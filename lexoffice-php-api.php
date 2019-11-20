<?php

/**
 * @copyright	2013-2019 | Baebeca Solutions GmbH
 * @author		Sebastian Lutz
 * @email		slutz@baebeca.de
 * @pgp			0x5AD0240C
 * @link		https://www.baebeca.de/softwareentwicklung/open-source-projekte/lexoffice-php-client/
 * @customer	-
 * @version		$Revision$
 * @date		$Date$
 * @license		GNU Affero General Public License v3.0
 * @license		If you need a copy under license for your closed software please contact us to get a business license
 **/

// Official Lexoffice Documentation: https://developers.lexoffice.io/docs/

class lexoffice_client {
	private $api_key;
	private $api_endpoint = 'https://api.lexoffice.io';
	private $api_version = 'v1';

	public function __construct($settings) {
		if (!is_array($settings)) throw new lexoffice_exception('lexoffice-php-api: settings should be an array');
		if (!array_key_exists('api_key', $settings)) throw new lexoffice_exception('lexoffice-php-api: no api_key is given');

		$this->api_key = $settings['api_key'];
		array_key_exists('callback', $settings) ? $this->callback = $settings['callback'] : $this->callback = false;
		array_key_exists('ssl_verify', $settings) ? $this->ssl_verify = $settings['ssl_verify'] : $this->ssl_verify = true;

		return true;
	}

	public function __destruct() {
		unset($this->api_key);
	}

	private function api_call($type, $resource, $uuid = '', $data = '', $params = '') {
		$ch = curl_init();
		$curl_url = $this->api_endpoint.'/'.$this->api_version.'/'.$resource.'/'.$uuid.$params;

		if ($type == 'GET') {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

			if ($resource == 'files') {
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Authorization: Bearer '.$this->api_key,
				));
			} else {
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Authorization: Bearer '.$this->api_key,
					'Accept: application/json',
				));
			}

		} elseif ($type == 'PUT') {
			$data = json_encode($data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Authorization: Bearer '.$this->api_key,
				'Content-Type: application/json',
				'Content-Length: '.strlen($data),
				'Accept: application/json',
			));
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		} elseif ($type == 'POST') {
			$data = json_encode($data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Authorization: Bearer '.$this->api_key,
				'Content-Type: application/json',
				'Content-Length: '.strlen($data),
				'Accept: application/json',
			));
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		} else {
			throw new lexoffice_exception('lexoffice-php-api: unknown request type "'.$type.'" for api_call');
		}

		curl_setopt($ch, CURLOPT_URL, $curl_url);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Baebeca Solutions GmbH - lexoffice-php-api | https://github.com/Baebeca-Solutions/lexoffice-php-api');

		// skip ssl verify only if manual deactivated (eg. in local tests)
		if (!$this->ssl_verify) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		} else {
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		}

		$result = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
		if ($http_status == 200 || $http_status == 201) {
			if (!empty($result) && $result && !($type == 'GET' && $resource == 'files')) {
				return json_decode($result);
			// binary
			} elseif (!empty($result) && $result) {
				return $result;
			} else {
				return true;
			}
		} else {
			throw new lexoffice_exception('lexoffice-php-api: error in api request - check details via $e->get_error()', array(
				'HTTP Status' => $http_status,
				'Requested URI' => $curl_url,
				'Requested Payload' => json_decode($data),
				'Response' => json_decode($result),
			));
		}

	}

	public function create_event($event, $callback = false) {
		if (!$callback) $callback = $this->callback;
		if ($callback) {
			return $this->api_call('POST', 'event-subscriptions', '', array('eventType' => $event, 'callbackUrl' => $callback));
		} else {
			return false;
		}
	}

	// todo
	#public function create_contact() {
	#
	#}

	public function create_invoice($data, $finalized = false) {
		//todo some validation checks
		return $this->api_call('POST', 'invoices', '', $data, ($finalized ? '?finalize=true' : ''));
	}

	public function get_event($uuid) {
		return $this->api_call('GET', 'event-subscriptions', $uuid);
	}

	public function get_events_all() {
		return $this->api_call('GET', 'event-subscriptions');
	}

	public function get_contact($uuid) {
		return $this->api_call('GET', 'contacts', $uuid);
	}

	public function get_contacts_all() {
		$result = $this->api_call('GET', 'contacts', '', '', '?page=0&size=100&direction=ASC&property=name');
		$contacts = $result->content;
		unset($result->content);

		for ($i = 1; $i < $result->totalPages; $i++) {
			$result_page = $this->api_call('GET', 'contacts', '', '', '?page='.$i.'&size=100&direction=ASC&property=name');
			foreach ($result_page->content as $contact) {
				$contacts[] = $contact;
			}
			unset($result_page->content);
		}
		return($contacts);
	}

	public function get_invoice($uuid) {
		return $this->api_call('GET', 'invoices', $uuid);
	}

	public function get_invoices_all() {
		$result = $this->api_call('GET', 'voucherlist', '', '', '?page=0&size=100&direction=ASC&sort=voucherNumber&voucherType=invoice,creditnote&voucherStatus=open,paid,paidoff,voided,transferred');
		$vouchers = $result->content;
		unset($result->content);

		for ($i = 1; $i < $result->totalPages; $i++) {
			$result_page = $this->api_call('GET', 'voucherlist', '', '', '?page='.$i.'&size=100&direction=ASC&sort=voucherNumber&voucherType=invoice,creditnote&voucherStatus=open,paid,paidoff,voided,transferred');
			foreach ($result_page->content as $voucher) {
				$vouchers[] = $voucher;
			}
			unset($result_page->content);
		}
		return($vouchers);
	}

	public function get_invoice_pdf($uuid, $filename) {
		// check if invoice is a draft
		$invoice = $this->get_invoice($uuid);
		if ($invoice->voucherStatus == 'draft') throw new lexoffice_exception('lexoffice-php-api: requested invoice is a draft. Cannot create/download pdf file. Check details via $e->get_error()', array('invoice_id' => $uuid));

		$request = $this->api_call('GET', 'invoices', $uuid, '', '/document');
		if ($request && isset($request->documentFileId)) {
			$request_file = $this->api_call('GET', 'files', $request->documentFileId);
			if ($request_file) {
				file_put_contents($filename, $request_file);
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function update_contact($uuid, $data) {
		return $this->api_call('PUT', 'contacts', $uuid, $data);
	}

	// todo check lifetime api key

	// todo
	#public function update_invoice() {
	#
	#}

	// todo
	#public function delete_event($uuid) {
	#
	#}

	// todo
	#public function search_contact() {
	#
	#}

}

class lexoffice_exception extends Exception {
	private $custom_error = '';
	public function __construct($message, $data = array()) {
		$this->custom_error = $data;
		parent::__construct($message);
	}

	public function get_error() {
		return $this->custom_error;
	}
}