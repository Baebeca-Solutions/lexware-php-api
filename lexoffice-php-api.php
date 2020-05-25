<?php

/**
 * @copyright	2013-2020 | Baebeca Solutions GmbH
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
	protected $api_key = '';
	protected $api_endpoint = 'https://api.lexoffice.io';
	protected $callback = '';
	protected $api_version = 'v1';

	public function __construct($settings) {
		if (!is_array($settings)) throw new lexoffice_exception('lexoffice-php-api: settings should be an array');
		if (!array_key_exists('api_key', $settings)) throw new lexoffice_exception('lexoffice-php-api: no api_key is given');

		$this->api_key = $settings['api_key'];
		array_key_exists('callback', $settings) ? $this->callback = $settings['callback'] : $this->callback = false;
		array_key_exists('ssl_verify', $settings) ? $this->ssl_verify = $settings['ssl_verify'] : $this->ssl_verify = true;
		if (array_key_exists('sandbox', $settings) && $settings['sandbox'] === true) $this->api_endpoint = 'https://api-sandbox.grld.eu';

		return true;
	}

	public function __destruct() {
		unset($this->api_key);
	}

	protected function api_call($type, $resource, $uuid = '', $data = '', $params = '') {
		// check api_key
		if ($this->api_key === true || $this->api_key === false || $this->api_key === '') throw new lexoffice_exception('lexoffice-php-api: invalid API Key', array('api_key' => $this->api_key));
		if (strlen($this->api_key) != 36 || substr_count($this->api_key, '-') != 4) throw new lexoffice_exception('lexoffice-php-api: invalid API Key', array('api_key' => $this->api_key));

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
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

			if (
				$resource == 'files' ||
				($resource == 'vouchers' && $params == '/files') // POST requests to endpoint "vouchers" only available in Partner-API
			) {
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Authorization: Bearer '.$this->api_key,
					'Content-Type: multipart/form-data',
					'Accept: application/json',
				));
				curl_setopt($ch, CURLOPT_POST, true);
			} else {
				$data = json_encode($data);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Authorization: Bearer '.$this->api_key,
					'Content-Type: application/json',
					'Content-Length: '.strlen($data),
					'Accept: application/json',
				));
			}
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		} elseif ($type == 'DELETE') {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Authorization: Bearer '.$this->api_key,
				'Accept: application/json',
			));
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
		}

		$result = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
		if ($http_status == 200 || $http_status == 201 || $http_status == 202 || $http_status == 204) {
			if (!empty($result) && $result && !($type == 'GET' && $resource == 'files')) {
				return json_decode($result);
				// binary
			} else if (!empty($result) && $result) {
				return $result;
			} else {
				return true;
			}
		} elseif ($http_status == 401) {
			throw new lexoffice_exception('lexoffice-php-api: invalid API Key', array(
				'HTTP Status' => $http_status,
				'Requested URI' => $curl_url,
				'Requested Payload' => json_decode($data),
				'Response' => json_decode($result),
			));
		} elseif ($http_status == 402) {
			throw new lexoffice_exception('lexoffice-php-api: action not possible due a lexoffice contract issue');
		} elseif ($http_status == 503) {
			throw new lexoffice_exception('lexoffice-php-api: API Service currently unavailable', array(
				'HTTP Status' => $http_status,
				'Requested URI' => $curl_url,
			));
		} else {
			// all other codes https://developers.lexoffice.io/docs/#http-status-codes
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
			throw new lexoffice_exception('lexoffice-php-api: cannot create webhook, no callback given');
		}
	}

	public function create_contact(array $data) {
		// todo maybe a good idea to check if already exist?
		// todo some validation checks
		// set version to 0 to create a new contact
		$data['version'] = 0;
		return $this->api_call('POST', 'contacts', '', $data);
	}

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
		$result = $this->api_call('GET', 'voucherlist', '', '', '?page=0&size=100&sort=voucherNumber,DESC&voucherType=invoice,creditnote&voucherStatus=open,paid,paidoff,voided,transferred');
		$vouchers = $result->content;
		unset($result->content);

		for ($i = 1; $i < $result->totalPages; $i++) {
			$result_page = $this->api_call('GET', 'voucherlist', '', '', '?page='.$i.'&size=100&sort=voucherNumber,DESC&voucherType=invoice,creditnote&voucherStatus=open,paid,paidoff,voided,transferred');
			foreach ($result_page->content as $voucher) {
				$vouchers[] = $voucher;
			}
			unset($result_page->content);
		}
		return($vouchers);
	}

	public function get_last_invoices($count) {
		if ($count <= 0) throw new lexoffice_exception('lexoffice-php-api: positive invoice count needed');

		if ($count <= 100) {
			$result = $this->api_call('GET', 'voucherlist', '', '', '?page=0&size='.$count.'&sort=voucherNumber,DESC&voucherType=invoice&voucherStatus=open,paid,paidoff,voided,transferred');
			return $result->content;
		} else {
			$result = $this->api_call('GET', 'voucherlist', '', '', '?page=0&size=100&sort=voucherNumber,DESC&voucherType=invoice&voucherStatus=open,paid,paidoff,voided,transferred');
			$vouchers = $result->content;
			$count = $count-100;
			unset($result->content);

			for ($i = 1; $i < $result->totalPages; $i++) {
				if (!$count) break;
				if ($count <= 100) {
					$count_tmp = $count;
				} else {
					$count_tmp = 100;
				}

				$result_page = $this->api_call('GET', 'voucherlist', '', '', '?page='.$i.'&size='.$count_tmp.'&sort=voucherNumber,DESC&voucherType=invoice&voucherStatus=open,paid,paidoff,voided,transferred');
				foreach ($result_page->content as $voucher) {
					$vouchers[] = $voucher;
				}
				$count = $count-$count_tmp;
				unset($result_page->content);
			}
			return $vouchers;
		}
	}

	public function get_quotation($uuid) {
		return $this->api_call('GET', 'quotations', $uuid);
	}

	/* legacy function - will be removed in futere releases */
	/* use get_pdf($type, $uuid, $filename) instead */
	public function get_invoice_pdf($uuid, $filename) {
		// check if invoice is a draft
		$invoice = $this->get_invoice($uuid);
		if ($invoice->voucherStatus == 'draft') throw new lexoffice_exception('lexoffice-php-api: requested invoice is a draft. Cannot create/download pdf file. Check details via $e->get_error()', array('invoice_id' => $uuid));

		return $this->get_pdf('invoices', $uuid, $filename);
	}

	public function get_pdf($type, $uuid, $filename) {
		$request = $this->api_call('GET', $type, $uuid, '', '/document');
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

	public function get_vouchers($type = 'invoice,creditnote,orderconfirmation', $state = 'draft,open,paid,paidoff,voided,transferred', $archived = 'both') {
		if ($archived == 'true') {
			$archived = '&archived=true';
		} elseif ($archived == 'false') {
			$archived = '&archived=false';
		} else {
			$archived = '';
		}

		$result = $this->api_call('GET', 'voucherlist', '', '', '?page=0&size=100&sort=voucherNumber,DESC&voucherType='.$type.'&voucherStatus='.$state.$archived);

		if (isset($result->content)) {
			$vouchers = $result->content;
			unset($result->content);

			for ($i = 1; $i < $result->totalPages; $i++) {
				$result_page = $this->api_call('GET', 'voucherlist', '', '', '?page='.$i.'&size=100&sort=voucherNumber,DESC&voucherType='.$type.'&voucherStatus='.$state.$archived);
				foreach ($result_page->content as $voucher) {
					$vouchers[] = $voucher;
				}
				unset($result_page->content);
			}
			return($vouchers);
		}
	}

	public function get_profile() {
		return $this->api_call('GET', 'profile');
	}

	public function get_credit_note($uuid) {
		return $this->api_call('GET', 'credit-notes', $uuid);
	}

	public function update_contact($uuid, $data) {
		return $this->api_call('PUT', 'contacts', $uuid, $data);
	}

	// todo
	#public function update_invoice() {
	#
	#}

	public function delete_event($uuid) {
		return $this->api_call('DELETE', 'event-subscriptions', $uuid);
	}

	public function search_contact(array $filters) {
		// todo integrate pagination

		$filter_string = '';
		foreach ($filters as $index => $filter) {
			if (($index == 'customer' || $index == 'vendor') && $filter !== '') {
				// bool to text
				if ($filter === true) $filter = 'true';
				if ($filter === false) $filter = 'false';
				$filter_string.= $index.'='.urlencode($filter).'&';
			} elseif (($index == 'email' || $index == 'name') && $filter !== '') {
				if (strlen($filter) < 3) throw new lexoffice_exception('lexoffice-php-api: search pattern must have least 3 characters');
				$filter_string.= $index.'='.urlencode($filter).'&';
			} elseif ($filter !== '') {
				$filter_string.= $index.'='.urlencode($filter).'&';
			}
		}

		if (!$filter_string) throw new lexoffice_exception('lexoffice-php-api: no valid filter for searching contacts');
		return $this->api_call('GET', 'contacts', '', '', '?'.substr($filter_string, 0, -1));
	}

	// todo check lifetime api key

	public function upload_file($file) {
		if (!file_exists($file)) throw new lexoffice_exception('lexoffice-php-api: file does not exist', array('file' => $file));
		if (filesize($file) > 5*1024*1024) throw new lexoffice_exception('lexoffice-php-api: filesize to big', array('file' => $file, 'filesize' => filesize($file).' byte'));
		if (!in_array(substr($file, -4), array('.pdf', '.jpg', '.png'))) throw new lexoffice_exception('lexoffice-php-api: invalid file extension', array('file' => $file));

		return $this->api_call('POST', 'files', '', array('file' => new CURLFile($file), 'type' => 'voucher'), '');
	}
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