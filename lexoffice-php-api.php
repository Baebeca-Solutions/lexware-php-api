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
    protected $countries;

    public function __construct($settings) {
        if (!is_array($settings)) throw new lexoffice_exception('lexoffice-php-api: settings should be an array');
        if (!array_key_exists('api_key', $settings)) throw new lexoffice_exception('lexoffice-php-api: no api_key is given');

        $this->api_key = $settings['api_key'];
        array_key_exists('callback', $settings) ? $this->callback = $settings['callback'] : $this->callback = false;
        array_key_exists('ssl_verify', $settings) ? $this->ssl_verify = $settings['ssl_verify'] : $this->ssl_verify = true;

        // sandboxes
        if (array_key_exists('sandbox', $settings) && $settings['sandbox'] === true) $this->api_endpoint = 'https://api-sandbox.grld.eu';
        if (array_key_exists('sandbox_oss', $settings) && $settings['sandbox_oss'] === true) $this->api_endpoint = 'https://api-oss-sandbox.grld.eu';

        $this->load_country_definition();

        return true;
    }

    private function load_country_definition() {
        // country definition | this is the curren definition which is legal today
        // tax adjustments in past or future will be checked later in
        $this->countries = (object)[
            /** nullrate | Nullsatz
             * https://europa.eu/youreurope/business/taxation/vat/vat-rules-rates/index_de.htm
             * Einige EU-Länder wenden auf bestimmte Umsätze einen Nullsatz an.
             * Bei Anwendung eines Nullsatzes muss der Verbraucher keine Mehrwertsteuer abführen,
             * Sie können jedoch Mehrwertsteuer, die Sie bei unmittelbar mit dem betreffenden Umsatz verbundenen
             * Einkäufen selbst entrichtet haben, in Abzug bringen.
             */

            /** europe_member | Europäischen Wirtschaftsraums (EWR) */

            'AT' => (object)[
                'title' => 'Österreich',
                'taxtitle' => 'USt',
                'taxrates' => (object)[
                    'default' => 20,
                    'reduced' => [10, 13],
                    'nullrate' => false,
                ],
                'europe_member' => true,
            ],
            'BE' => (object)[
                'title' => 'Belgien',
                'taxtitle' => 'TVA',
                'taxrates' => (object)[
                    'default' => 21,
                    'reduced' => [6, 12],
                    'nullrate' => true,
                ],
                'europe_member' => true,
            ],
            'BG' => (object)[
                'title' => 'Bulgarien',
                'taxtitle' => 'DDS',
                'taxrates' => (object)[
                    'default' => 20,
                    'reduced' => [9],
                    'nullrate' => false,
                ],
                'europe_member' => true,
            ],
            'CH' => (object)[
                'title' => 'Schweiz',
                'taxtitle' => 'VAT',
                'taxrates' => (object)[
                    'default' => 7.7,
                    'reduced' => [2.5, 3.7],
                    'nullrate' => false,
                ],
                'europe_member' => false,
            ],
            'CY' => (object)[
                'title' => 'Zypern',
                'taxtitle' => 'FPA',
                'taxrates' => (object)[
                    'default' => 19,
                    'reduced' => [5, 9],
                    'nullrate' => true,
                ],
                'europe_member' => true,
            ],
            'CZ' => (object)[
                'title' => 'Tschechische Republik',
                'taxtitle' => 'DPH',
                'taxrates' => (object)[
                    'default' => 21,
                    'reduced' => [10, 15],
                    'nullrate' => false,
                ],
                'europe_member' => true,
            ],
            'DK' => (object)[
                'title' => 'Dänemark',
                'taxtitle' => 'MOMS',
                'taxrates' => (object)[
                    'default' => 25,
                    'reduced' => [],
                    'nullrate' => true,
                ],
                'europe_member' => true,
            ],
            'DE' => (object)[
                'title' => 'Deutschland',
                'taxtitle' => 'USt',
                'taxrates' => (object)[
                    'default' => 19,
                    'reduced' => [7],
                    'nullrate' => false,
                ],
                'europe_member' => true,
            ],
            'EE' => (object)[
                'title' => 'Estland',
                'taxtitle' => 'KMKR',
                'taxrates' => (object)[
                    'default' => 20,
                    'reduced' => [9],
                    'nullrate' => false,
                ],
                'europe_member' => true,
            ],
            'ES' => (object)[
                'title' => 'Spanien',
                'taxtitle' => 'IVA',
                'taxrates' => (object)[
                    'default' => 21,
                    'reduced' => [4, 10],
                    'nullrate' => false,
                ],
                'europe_member' => true,
            ],
            'FI' => (object)[
                'title' => 'Finnland',
                'taxtitle' => 'AVL',
                'taxrates' => (object)[
                    'default' => 24,
                    'reduced' => [10, 14],
                    'nullrate' => true,
                ],
                'europe_member' => true,
            ],
            'FR' => (object)[
                'title' => 'Frankreich',
                'taxtitle' => 'TVA',
                'taxrates' => (object)[
                    'default' => 20,
                    'reduced' => [2.1, 5.5, 10],
                    'nullrate' => false,
                ],
                'europe_member' => true,
            ],
            'GR' => (object)[
                'title' => 'Griechenland',
                'taxtitle' => 'FPA',
                'taxrates' => (object)[
                    'default' => 24,
                    'reduced' => [6, 13],
                    'nullrate' => false,
                ],
                'europe_member' => true,
            ],
            'GB' => (object)[
                'title' => 'Großbritannien',
                'taxtitle' => 'VAT',
                'taxrates' => (object)[
                    'default' => 20,
                    'reduced' => [5],
                    'nullrate' => true,
                ],
                'europe_member' => false,
            ],
            'IE' => (object)[
                'title' => 'Irland',
                'taxtitle' => 'VAT',
                'taxrates' => (object)[
                    'default' => 23,
                    'reduced' => [4.8, 9, 13.5],
                    'nullrate' => true,
                ],
                'europe_member' => true,
            ],
            'IT' => (object)[
                'title' => 'Italien',
                'taxtitle' => 'IVA',
                'taxrates' => (object)[
                    'default' => 22,
                    'reduced' => [4, 5, 10],
                    'nullrate' => true,
                ],
                'europe_member' => true,
            ],
            'HR' => (object)[
                'title' => 'Kroatien',
                'taxtitle' => 'PDV',
                'taxrates' => (object)[
                    'default' => 25,
                    'reduced' => [5, 13],
                    'nullrate' => false,
                ],
                'europe_member' => true,
            ],
            'LV' => (object)[
                'title' => 'Lettland',
                'taxtitle' => 'PVN',
                'taxrates' => (object)[
                    'default' => 21,
                    'reduced' => [5, 12],
                    'nullrate' => false,
                ],
                'europe_member' => true,
            ],
            'LT' => (object)[
                'title' => 'Litauen',
                'taxtitle' => 'PVM',
                'taxrates' => (object)[
                    'default' => 21,
                    'reduced' => [5, 9],
                    'nullrate' => false,
                ],
                'europe_member' => true,
            ],
            'LU' => (object)[
                'title' => 'Luxemburg',
                'taxtitle' => 'TVA',
                'taxrates' => (object)[
                    'default' => 17,
                    'reduced' => [3, 8, 14],
                    'nullrate' => false,
                ],
                'europe_member' => true,
            ],
            'MT' => (object)[
                'title' => 'Malta',
                'taxtitle' => 'VAT',
                'taxrates' => (object)[
                    'default' => 18,
                    'reduced' => [5, 7],
                    'nullrate' => true,
                ],
                'europe_member' => true,
            ],
            'NL' => (object)[
                'title' => 'Niederlande',
                'taxtitle' => 'OB',
                'taxrates' => (object)[
                    'default' => 21,
                    'reduced' => [9],
                    'nullrate' => false,
                ],
                'europe_member' => true,
            ],
            'PL' => (object)[
                'title' => 'Polen',
                'taxtitle' => 'VAT',
                'taxrates' => (object)[
                    'default' => 23,
                    'reduced' => [5, 8],
                    'nullrate' => true,
                ],
                'europe_member' => true,
            ],
            'PT' => (object)[
                'title' => 'Portugal',
                'taxtitle' => 'IVA',
                'taxrates' => (object)[
                    'default' => 23,
                    'reduced' => [6, 13],
                    'nullrate' => false,
                ],
                'europe_member' => true,
            ],
            'RO' => (object)[
                'title' => 'Rumänien',
                'taxtitle' => 'TVA',
                'taxrates' => (object)[
                    'default' => 19,
                    'reduced' => [5, 9],
                    'nullrate' => false,
                ],
                'europe_member' => true,
            ],
            'SE' => (object)[
                'title' => 'Schweden',
                'taxtitle' => 'ML',
                'taxrates' => (object)[
                    'default' => 25,
                    'reduced' => [6, 12],
                    'nullrate' => true,
                ],
                'europe_member' => true,
            ],
            'SK' => (object)[
                'title' => 'Slowakische Republik',
                'taxtitle' => 'DPH',
                'taxrates' => (object)[
                    'default' => 20,
                    'reduced' => [10],
                    'nullrate' => false,
                ],
                'europe_member' => true,
            ],
            'SI' => (object)[
                'title' => 'Slowenien',
                'taxtitle' => 'DDV',
                'taxrates' => (object)[
                    'default' => 22,
                    'reduced' => [5, 9.5],
                    'nullrate' => false,
                ],
                'europe_member' => true,
            ],
            'HU' => (object)[
                'title' => 'Ungarn',
                'taxtitle' => 'AFA',
                'taxrates' => (object)[
                    'default' => 27,
                    'reduced' => [5, 18],
                    'nullrate' => false,
                ],
                'europe_member' => true,
            ],
            'IS' => (object)[
                'title' => 'Island',
                'taxtitle' => 'VSK',
                'taxrates' => (object)[
                    'default' => 24,
                    'reduced' => [11],
                    'nullrate' => false,
                ],
                'europe_member' => false,
            ],
            'LI' => (object)[
                'title' => 'Liechtenstein',
                'taxtitle' => 'VAT',
                'taxrates' => (object)[
                    'default' => 7.7,
                    'reduced' => [2.5, 3.7],
                    'nullrate' => false,
                ],
                'europe_member' => false,
            ],
            'NO' => (object)[
                'title' => 'Norwegen',
                'taxtitle' => 'MVA',
                'taxrates' => (object)[
                    'default' => 25,
                    'reduced' => [11.11, 12, 15],
                    'nullrate' => false,
                ],
                'europe_member' => false,
            ],
        ];
    }

    public function __destruct() {
        unset($this->api_key);
    }

    protected function api_call($type, $resource, $uuid = '', $data = '', $params = '', $return_http_header = false, $repeatable = true) {
        // check api_key
        if ($this->api_key === true || $this->api_key === false || $this->api_key === '') throw new lexoffice_exception('lexoffice-php-api: invalid API Key', ['api_key' => $this->api_key]);

        $ch = curl_init();
        $curl_url = $this->api_endpoint.'/'.$this->api_version.'/'.$resource.'/'.$uuid.$params;

        if ($type == 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

            if ($resource == 'files') {
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer '.$this->api_key,
                ]);
            } else {
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer '.$this->api_key,
                    'Accept: application/json',
                ]);
            }

        } elseif ($type == 'PUT') {
            $data = json_encode($data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer '.$this->api_key,
                'Content-Type: application/json',
                'Content-Length: '.strlen($data),
                'Accept: application/json',
            ]);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        } elseif ($type == 'POST') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

            if (
                $resource == 'files' ||
                ($resource == 'vouchers' && $params == '/files') // POST requests to endpoint "vouchers" only available in Partner-API
            ) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer '.$this->api_key,
                    'Content-Type: multipart/form-data',
                    'Accept: application/json',
                ]);
                curl_setopt($ch, CURLOPT_POST, true);
            } else {
                $data = json_encode($data);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer '.$this->api_key,
                    'Content-Type: application/json',
                    'Content-Length: '.strlen($data),
                    'Accept: application/json',
                ]);
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        } elseif ($type == 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer '.$this->api_key,
                'Accept: application/json',
            ]);
        } else {
            throw new lexoffice_exception('lexoffice-php-api: unknown request type "'.$type.'" for api_call');
        }

        curl_setopt($ch, CURLOPT_URL, $curl_url);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, $return_http_header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Baebeca Solutions GmbH - lexoffice-php-api | https://github.com/Baebeca-Solutions/lexoffice-php-api');

        // skip ssl verify only if manual deactivated (eg. in local tests)
        if (!$this->ssl_verify) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

        // prepare data for error message
        if ($data !== '' && is_string($data)) $data = json_decode($data);

        if ($http_status == 200 || $http_status == 201 || $http_status == 202 || $http_status == 204) {
            if (!empty($result) && $result && !($type == 'GET' && $resource == 'files') && !$return_http_header) {
                return json_decode($result);
                // full http_header
            } else if (!empty($result) && $result && $return_http_header) {
                return ['header' => curl_getinfo($ch), 'body' => $result];
                // binary or full http_header
            } else if (!empty($result) && $result) {
                return $result;
            } else {
                return true;
            }
        } elseif ($http_status == 400) {
            throw new lexoffice_exception('lexoffice-php-api: Malformed syntax or a bad query', [
                'HTTP Status' => $http_status,
                'Requested URI' => $curl_url,
                'Requested Payload' => $data,
                'Response' => json_decode($result),
            ]);
        } elseif ($http_status == 401) {
            throw new lexoffice_exception('lexoffice-php-api: invalid API Key', [
                'HTTP Status' => $http_status,
                'Requested URI' => $curl_url,
                'Requested Payload' => $data,
                'Response' => json_decode($result),
            ]);
        } elseif ($http_status == 402) {
            throw new lexoffice_exception('lexoffice-php-api: action not possible due a lexoffice contract issue');
        } elseif ($http_status == 403) {
            throw new lexoffice_exception('lexoffice-php-api: Authenticated but insufficient scope or insufficient access rights in lexoffice', [
                'HTTP Status' => $http_status,
                'Requested URI' => $curl_url,
                'Requested Payload' => $data,
                'Response' => json_decode($result),
            ]);
        } elseif ($http_status == 404) {
            throw new lexoffice_exception('lexoffice-php-api: Requested resource does no exist (anymore)', [
                'HTTP Status' => $http_status,
                'Requested URI' => $curl_url,
                'Requested Payload' => $data,
                'Response' => json_decode($result),
            ]);
        } elseif ($http_status == 405) {
            throw new lexoffice_exception('lexoffice-php-api: Method not allowed on resource', [
                'HTTP Status' => $http_status,
                'Requested URI' => $curl_url,
                'Requested Payload' => $data,
                'Response' => json_decode($result),
            ]);
            // rate limit, repeat
        } elseif ($http_status == 429 && $repeatable === true) {
            sleep(3);
            return $this->api_call($type, $resource, $uuid, $data, $params, $return_http_header, false);
        } elseif ($http_status == 429) {
            throw new lexoffice_exception('lexoffice-php-api: Endpoint exceeds the limit of throttling. This request should be called again at a later time', [
                'HTTP Status' => $http_status,
                'Requested URI' => $curl_url,
                'Requested Payload' => $data,
                'Response' => json_decode($result),
            ]);
        } elseif ($http_status == 500) {
            throw new lexoffice_exception('lexoffice-php-api: Internal server error.', [
                'HTTP Status' => $http_status,
                'Requested URI' => $curl_url,
                'Requested Payload' => $data,
                'Response' => json_decode($result),
            ]);
        } elseif ($http_status == 503) {
            throw new lexoffice_exception('lexoffice-php-api: API Service currently unavailable', [
                'HTTP Status' => $http_status,
                'Requested URI' => $curl_url,
                'Requested Payload' => $data,
                'Response' => json_decode($result),
            ]);
        } else {
            // all other codes https://developers.lexoffice.io/docs/#http-status-codes
            throw new lexoffice_exception('lexoffice-php-api: error in api request - check details via $e->get_error()', [
                'HTTP Status' => $http_status,
                'Requested URI' => $curl_url,
                'Requested Payload' => $data,
                'Response' => json_decode($result),
            ]);
        }
    }

    public function create_event($event, $callback = false) {
        if (!$callback) $callback = $this->callback;
        if ($callback) {
            return $this->api_call('POST', 'event-subscriptions', '', ['eventType' => $event, 'callbackUrl' => $callback]);
        } else {
            throw new lexoffice_exception('lexoffice-php-api: cannot create webhook, no callback given');
        }
    }

    public function create_contact(array $data) {
        $data = $this->validate_contact_data($data);

        // set version to 0 to create a new contact
        $data['version'] = 0;
        $new_contact = $this->api_call('POST', 'contacts', '', $data);

        // #73917
        // support a technical race condition in lexoffice database system
        // lexoffice statement: we have to wait 500ms before we can do anything with the delivered contact id because clustersync need synctime
        // 202202 increased to 700ms because sometimes 500ms is not enough :/
        // #88420 202206 increased to 1s because sometimes 700ms is not enough :/
        // #90527 202208 increased to 2s because sometimes 1s is not enough :/
        sleep(2);

        return $new_contact;
    }

    public function create_quotation($data, $finalized = false) {
        return $this->api_call('POST', 'quotations', '', $data, ($finalized ? '?finalize=true' : ''));
    }

    public function create_creditnote($data, $finalized = false, $linked_invoice_id = '') {
        $params_url = '';
        $params = [];
        if ($finalized) $params[] = 'finalize=true';
        if (!empty($linked_invoice_id)) $params[] = 'precedingSalesVoucherId='.$linked_invoice_id;

        if (!empty($params)) $params_url = '?'.implode('&', $params);

        return $this->api_call('POST', 'credit-notes', '', $data, $params_url);
    }

    public function create_invoice($data, $finalized = false) {
        //todo some validation checks
        return $this->api_call('POST', 'invoices', '', $data, ($finalized ? '?finalize=true' : ''));
    }

    public function create_orderconfirmation($data) {
        //todo some validation checks
        return $this->api_call('POST', 'order-confirmations', '', $data);
    }

    public function create_voucher($data) {
        return $this->api_call('POST', 'vouchers', '', $data);
    }

    public function create_delivery_note($data) {
        return $this->api_call('POST', 'delivery-notes', '', $data);
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
        $result = $this->api_call('GET', 'contacts', '', '', '?page=0&size=250&direction=ASC&property=name');
        $contacts = $result->content;
        unset($result->content);

        for ($i = 1; $i < $result->totalPages; $i++) {
            $result_page = $this->api_call('GET', 'contacts', '', '', '?page='.$i.'&size=250&direction=ASC&property=name');
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

    public function get_down_payment_invoice($uuid) {
        return $this->api_call('GET', 'down-payment-invoices', $uuid);
    }

    public function get_quotation($uuid) {
        return $this->api_call('GET', 'quotations', $uuid);
    }

    public function get_orderconfirmation($uuid) {
        return $this->api_call('GET', 'order-confirmations', $uuid);
    }

    public function get_deliverynote($uuid) {
        return $this->api_call('GET', 'delivery-notes', $uuid);
    }

    /* legacy function - will be removed in futere releases */
    /* use get_pdf($type, $uuid, $filename) instead */
    public function get_invoice_pdf($uuid, $filename) {
        // check if invoice is a draft
        $invoice = $this->get_invoice($uuid);
        if ($invoice->voucherStatus == 'draft') throw new lexoffice_exception('lexoffice-php-api: requested invoice is a draft. Cannot create/download pdf file. Check details via $e->get_error()', ['invoice_id' => $uuid]);

        return $this->get_pdf('invoices', $uuid, $filename);
    }

    public function get_pdf($type, $uuid, $filename): bool {
        if ($type === 'downpaymentinvoice') {
            $request = $this->get_down_payment_invoice($uuid);
            if (empty($request->files->documentFileId)) return false;
            $request_file = $this->api_call('GET', 'files', $request->files->documentFileId);
            if ($request_file) {
                file_put_contents($filename, $request_file);
                return true;
            }
            return false;
        }
        else {
            $request = $this->api_call('GET', $type, $uuid, '', '/document');
            if ($request && isset($request->documentFileId)) {
                $request_file = $this->api_call('GET', 'files', $request->documentFileId);
                if ($request_file) {
                    file_put_contents($filename, $request_file);
                    return true;
                }
                return false;
            }
            return false;
        }
    }

    public function get_voucher($uuid) {
        return $this->api_call('GET', 'vouchers', $uuid);
    }

    public function get_vouchers(
        $type = 'invoice,creditnote,orderconfirmation',
        $state = 'draft,open,paid,paidoff,voided,accepted,rejected',
        $archived = 'both',
        $date_from = '',
        $date_to = ''
    ) {
        $filter_archived = '';
        if ($archived == 'true' || $archived === true) {
            $filter_archived = '&archived=true';
        } elseif ($archived == 'false' || $archived === false) {
            $filter_archived = '&archived=false';
        }

        $filter_date_from = '';
        if (!empty($date_from)) $filter_date_from = '&voucherDateFrom='.$date_from;
        $filter_date_to = '';
        if (!empty($date_to)) $filter_date_to = '&voucherDateTo='.$date_to;

        $result = $this->api_call(
            'GET',
            'voucherlist',
            '',
            '',
            '?page=0&size=250&sort=voucherNumber,DESC&voucherType='.$type.'&voucherStatus='.$state.$filter_archived.$filter_date_from.$filter_date_to
        );

        // #69724 - warning - lexoffice::init::vouchers
        // at the moment it is not possible to request more than 10K items due lexoffice internal restrictions
        // the lexoffice-API will throw an HTTP 500, so lets abort it until lexoffice has integrated a solution for this limitation
        // check it here: https://github.com/Baebeca-Solutions/lexoffice-php-api/issues/31
        if ($result->totalPages >= 40) { // 40 pages * 250 items == 10k
            // we have to split it up in smaller requests
            // lets start again
            $result = [];

            if (empty($date_from)) $date_from = '2011-01-01'; // set start day to lexoffice deployment date

            // store stop timetsamp
            $date_to_timestamp_end = time();
            if (!empty($date_to)) $date_to_timestamp_end = strtotime($date_to.'T00:00:00.000+01:00');

            // get next timespan
            $date_to_timestamp = strtotime($date_from.'T00:00:00.000+01:00')+(60*60*24*30); // +1 Month
            // reduce if lower timeframe was set by user
            if ($date_to_timestamp > $date_to_timestamp_end) $date_to_timestamp = $date_to_timestamp_end;
            $date_to = date('Y-m-d', $date_to_timestamp);

            // execute until we have reached today or date_to
            while (strtotime($date_from.'T00:00:00.000+01:00') < strtotime($date_to.'T00:00:00.000+01:00')) {
                $result_tmp = $this->get_vouchers($type, $state, $archived, $date_from, $date_to);

                // extract results
                foreach ($result_tmp as $tmp) {
                    array_push($result, $tmp);
                }
                unset($result_tmp, $tmp); // cleanup

                // get next timespan, update +1 month
                $date_from = date('Y-m-d', strtotime($date_to.'T00:00:00.000+01:00')+(60*60*25)); // +1 day (dont need same day twice)
                $date_to_timestamp = strtotime($date_from.'T00:00:00.000+01:00')+(60*60*24*30); // +1 month
                // reduce if lower timeframe was set by user
                if ($date_to_timestamp > $date_to_timestamp_end) $date_to_timestamp = $date_to_timestamp_end;
                $date_to = date('Y-m-d', $date_to_timestamp);
            }

            return $result;
        }

        if (isset($result->content)) {
            $vouchers = $result->content;
            unset($result->content);

            for ($i = 1; $i < $result->totalPages; $i++) {
                $result_page = $this->api_call('GET', 'voucherlist', '', '', '?page='.$i.'&size=250&sort=voucherNumber,DESC&voucherType='.$type.'&voucherStatus='.$state.$filter_archived.$filter_date_from.$filter_date_to);
                foreach ($result_page->content as $voucher) {
                    $vouchers[] = $voucher;
                }
                unset($result_page->content);
            }
            return($vouchers);
        }
        return [];
    }

    public function get_voucher_files($uuid, $filename_prefix): array {
        // must get voucher files before
        $voucher = $this->get_voucher($uuid);
        if (!$voucher || !isset($voucher->files[0])) throw new lexoffice_exception('lexoffice-php-api: voucher has no files. Cannot download file. Check details via $e->get_error()', ['voucher_id' => $uuid]);

        // iterate files
        $i = 1;
        $saved_files = [];
        foreach ($voucher->files as $uuid_file) {
            $request = $this->api_call('GET', 'files', $uuid_file, '', '', true);

            // unsused at the moment
            //$header = substr($request['body'], 0, $request['header']['header_size']);
            $body = substr($request['body'], $request['header']['header_size']);

            // content type
            switch ($request['header']['content_type']) {
                case 'image/png':
                    $extension = 'png';
                    break;
                case 'image/jpg':
                case 'image/jpeg':
                    $extension = 'jpg';
                    break;
                case 'application/pdf':
                    $extension = 'pdf';
                    break;
                default:
                    throw new lexoffice_exception('lexoffice-php-api: unknown mime/type "'.$request['header']['content_type'].'". Check details via $e->get_error()', ['voucher_id' => $uuid, 'response' => $request]);
            }

            $filename = $filename_prefix.'_'.$i.'.'.$extension;
            file_put_contents($filename, $body);
            $saved_files[] = $filename;
            $i++;
        }
        return $saved_files;
    }

    public function get_voucher_payments($uuid) {
        return $this->api_call('GET', 'payments', $uuid);
    }

    private $cache_profile = null;
    public function get_profile() {
        if (!is_null($this->cache_profile)) return $this->cache_profile;
        $this->cache_profile = $this->api_call('GET', 'profile');
        return $this->cache_profile;
    }

    public function get_creditnote($uuid) {
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
            // check if is not already encoded
            if (strpos($filter, '%') === false || $filter == rawurldecode($filter)) {
                $filter = rawurlencode($filter);
            }

            // replace spacer | sometimes on appended lastname which is already encoded and skipped above
            $filter = str_replace(' ', '%20', $filter);

            if (($index == 'customer' || $index == 'vendor') && $filter !== '') {
                // bool to text
                if ($filter === true) $filter = 'true';
                if ($filter === false) $filter = 'false';
                $filter_string.= $index.'='.$filter.'&';
            } elseif (($index == 'email' || $index == 'name') && $filter !== '') {
                if (strlen($filter) < 3) throw new lexoffice_exception('lexoffice-php-api: search pattern must have least 3 characters');
                $filter_string.= $index.'='.$filter.'&';
            } elseif ($filter !== '') {
                $filter_string.= $index.'='.$filter.'&';
            }
        }

        if (!$filter_string) throw new lexoffice_exception('lexoffice-php-api: no valid filter for searching contacts');
        return $this->api_call('GET', 'contacts', '', '', '?'.substr($filter_string, 0, -1));
    }

    // todo check lifetime api key

    public function upload_file($file) {
        if (!file_exists($file)) throw new lexoffice_exception('lexoffice-php-api: file does not exist', ['file' => $file]);
        if (filesize($file) > 5*1024*1024) throw new lexoffice_exception('lexoffice-php-api: filesize to big', ['file' => $file, 'filesize' => filesize($file).' byte']);
        // use mimetype type from filename
        if (
            in_array(substr(strtolower($file), -4), ['.pdf', '.jpg', '.png']) ||
            in_array(substr(strtolower($file), -5), ['.jpeg'])
        ) {
            return $this->api_call('POST', 'files', '', ['file' => new CURLFile($file), 'type' => 'voucher']);
        }

        // use file mimetype (lexoffice requires a fileextension in the filename :/)
        $mime_type = mime_content_type($file);
        switch ($mime_type) {
            case 'application/pdf':
                $dummy_title = 'dummy.pdf';
                break;
            case 'image/png':
                $dummy_title = 'dummy.png';
                break;
            case 'image/jpeg':
                $dummy_title = 'dummy.jpg';
                break;
            default:
                throw new lexoffice_exception('lexoffice-php-api: invalid mime type', ['file' => $file]);
        }
        return $this->api_call('POST', 'files', '', ['file' => new CURLFile($file, $mime_type, $dummy_title), 'type' => 'voucher']);
    }

    public function upload_voucher($uuid, $file) {
        if (!file_exists($file)) throw new lexoffice_exception('lexoffice-php-api: file does not exist', ['file' => $file]);
        if (filesize($file) > 5*1024*1024) throw new lexoffice_exception('lexoffice-php-api: filesize to big', ['file' => $file, 'filesize' => filesize($file).' byte']);
        // use mimetype type from filename
        if (
            in_array(substr(strtolower($file), -4), ['.pdf', '.jpg', '.png']) ||
            in_array(substr(strtolower($file), -5), ['.jpeg'])
        ) {
            return $this->api_call('POST', 'vouchers', $uuid, ['file' => new CURLFile($file)], '/files');
        }

        // use file mimetype (lexoffice requires a fileextension in the filename :/)
        $mime_type = mime_content_type($file);
        switch ($mime_type) {
            case 'application/pdf':
                $dummy_title = 'dummy.pdf';
                break;
            case 'image/png':
                $dummy_title = 'dummy.png';
                break;
            case 'image/jpeg':
                $dummy_title = 'dummy.jpg';
                break;
            default:
                throw new lexoffice_exception('lexoffice-php-api: invalid mime type', ['file' => $file]);
        }
        return $this->api_call('POST', 'vouchers', $uuid, ['file' => new CURLFile($file, $mime_type, $dummy_title), 'type' => 'voucher'], '/files');
    }

    /* Tax methods */

    public function is_tax_free_company(): bool {
        $profile = $this->get_profile();
        return !empty($profile->smallBusiness) && $profile->smallBusiness;
    }

    /**
     * Check if the given country is member in the european union
     * @param $country_code string 2-letter country code
     * @param $date int timestamp booking date
     * @return bool
     */
    public function is_european_member(string $country_code, int $date): bool {
        // load country definition, needed in extending classes with own constructor
        if (is_null($this->countries)) $this->load_country_definition();

        // use $date for future EU changes if needed
        return isset($this->countries->{strtoupper($country_code)}) && $this->countries->{strtoupper($country_code)}->europe_member;
    }

    /**
     * @param float $taxrate used taxrate for this item
     * @param string $country_code 2-letter customer country code
     * @param int $date booking date timestamp
     * @param bool $euopean_vatid customer use a vatid
     * @param bool $b2b_business customer is a b2b customer
     * @param bool $physical_good a physical good will be selled
     * @return string
     * @throws \lexoffice_exception
     */
    public function get_needed_voucher_booking_id(float $taxrate, string $country_code, int $date, bool $euopean_vatid, bool $b2b_business, bool $physical_good = true): string {
        // Weltweit, Kleinunternehmer
        if ($this->is_tax_free_company()) return '7a1efa0e-6283-4cbf-9583-8e88d3ba5960'; // §19 Kleinunternehmer

        // Deutschland
        if (strtoupper($country_code) == 'DE' && $physical_good) return '8f8664a8-fd86-11e1-a21f-0800200c9a66'; // Einnahmen -> Warenlieferung
        if (strtoupper($country_code) == 'DE') return '8f8664a0-fd86-11e1-a21f-0800200c9a66'; // Einnahmen

        // Europa
        if ($this->is_european_member($country_code, $date)) {
            // B2B - Warenlieferung
            if ($taxrate == 0 && $euopean_vatid && $b2b_business && $physical_good) return '9075a4e3-66de-4795-a016-3889feca0d20'; // Innergemeinschaftliche Lieferung
            // B2B - Dienstleistung
            if ($taxrate == 0 && $euopean_vatid && $b2b_business && !$physical_good) return '380a20cb-d04c-426e-b49c-84c22adfa362'; // Fremdleistungen §13b

            // Check OSS Stuff
            $oss = $this->is_oss_needed($country_code, $date);

            // Europa B2C
            // Waren und Dienstleistungen ohne OSS
            if ($oss === false) return '8f8664a1-fd86-11e1-a21f-0800200c9a66'; // Einnahmen

            // oss "destination" configuration (we have to check with target country taxrates)
            if ($oss === 'destination') {
                // check oss tax rate
                $oss_taxrates = $this->get_taxrates($country_code, $date);

                if (empty($oss_taxrates)) throw new lexoffice_exception('lexoffice-php-api: unknown OSS booking scenario, cannot decide correct taxrates', [
                    'taxrate' => $taxrate,
                    'country_code' => $country_code,
                    'european_vatid' => $euopean_vatid,
                    'b2b_business' => $b2b_business,
                    'physical_good' => $physical_good,
                ]);

                if (!$this->check_taxrate($taxrate, $country_code, $date)) throw new lexoffice_exception('lexoffice-php-api: invalid OSS taxrate for given country', [
                    'type' => 'destination',
                    'taxrate' => $taxrate,
                    'country_code' => $country_code,
                    'date' => $date,
                    'european_vatid' => $euopean_vatid,
                    'b2b_business' => $b2b_business,
                    'physical_good' => $physical_good,
                    'oss_valid_taxrates' => $oss_taxrates,
                ]);

                return $this->get_oss_voucher_category($country_code, $date, ($physical_good ? 1 : 2), $taxrate);
            }

            // oss "origin" configuration (we have to check with DE taxrates)
            if ($oss === 'origin') {
                $oss_taxrates = $this->get_taxrates('DE', $date);

                if (empty($oss_taxrates)) throw new lexoffice_exception('lexoffice-php-api: unknown OSS booking scenario, cannot decide correct taxrates', [
                    'taxrate' => $taxrate,
                    'country_code' => 'DE',
                    'european_vatid' => $euopean_vatid,
                    'b2b_business' => $b2b_business,
                    'physical_good' => $physical_good,
                ]);

                if (!$this->check_taxrate($taxrate, 'DE', $date)) throw new lexoffice_exception('lexoffice-php-api: invalid OSS taxrate for given country', [
                    'type' => 'origin',
                    'taxrate' => $taxrate,
                    'country_code' => 'DE',
                    'date' => $date,
                    'european_vatid' => $euopean_vatid,
                    'b2b_business' => $b2b_business,
                    'physical_good' => $physical_good,
                    'oss_valid_taxrates' => $oss_taxrates,
                ]);

                return $this->get_oss_voucher_category($country_code, $date, ($physical_good ? 1 : 2), $taxrate);
            }

            throw new lexoffice_exception('lexoffice-php-api: unknown OSS configuration', [
                'oss' => $oss,
            ]);
        }

        // Welt (inkl. Schweiz) - Warenlieferung
        if ($physical_good) {
            // B2B
            if ($taxrate == 0 && $b2b_business) return '93d24c20-ea84-424e-a731-5e1b78d1e6a9'; // Ausfuhrlieferungen an Drittländer
            // B2C
            if ($taxrate == 0 && !$b2b_business) return '8f8664a1-fd86-11e1-a21f-0800200c9a66'; // Einnahmen

            throw new lexoffice_exception('lexoffice-php-api: unknown booking scenario, world shipping with taxes. cannot decide correct booking category', [
                'taxrate' => $taxrate,
                'country_code' => $country_code,
                'date' => $date,
                'european_vatid' => $euopean_vatid,
                'b2b_business' => $b2b_business,
                'physical_good' => $physical_good,
            ]);
        }
        // Welt (inkl. Schweiz) - Dienstleistung
        else {
            // B2B
            if ($taxrate == 0 && $b2b_business) return 'ef5b1a6e-f690-4004-9a19-91276348894f'; // Dienstleistung an Drittländer
            // B2C
            if ($taxrate == 0 && !$b2b_business) return '8f8664a1-fd86-11e1-a21f-0800200c9a66'; // Einnahmen | #87248 - API - individual_person_not_applicable_service_third_party (lexoffice does not support the correct booking type)

            // Welt (inkl. Schweiz) - B2C
            #if ($taxrate > 0) return '8f8664a1-fd86-11e1-a21f-0800200c9a66'; // Einnahmen

            throw new lexoffice_exception('lexoffice-php-api: unknown booking scenario, world service with taxes. cannot decide correct booking category', [
                'taxrate' => $taxrate,
                'country_code' => $country_code,
                'date' => $date,
                'european_vatid' => $euopean_vatid,
                'b2b_business' => $b2b_business,
                'physical_good' => $physical_good,
            ]);
        }

        throw new lexoffice_exception('lexoffice-php-api: unknown booking scenario, cannot decide correct booking category', [
            'taxrate' => $taxrate,
            'country_code' => $country_code,
            'date' => $date,
            'european_vatid' => $euopean_vatid,
            'b2b_business' => $b2b_business,
            'physical_good' => $physical_good,
        ]);
    }

    /**
     * Returns an array with the possible taxrates for the given country
     * @param string $country_code  2-letter country code
     * @param int    $date          booking date timestamp
     * @return array if $return_unsorted = false
     *  [
     *      'default' => 19,
     *      'reduced' => [7, 5],
     *      'nullrate' => false
     *  ]
     * array if $return_unsorted = true
     *  [ 19, 7, 5 ]
     */
    public function get_taxrates(string $country_code, int $date, $return_unsorted = false): array {
        // load country definition, needed in extending classes with own constructor
        if (is_null($this->countries)) $this->load_country_definition();

        // unknown country
        if (empty($this->countries->{strtoupper($country_code)})) {
            if (!$return_unsorted) return [
                'default' => null,
                'reduced' => [0]
            ];
            return [0];
        }

        $taxrates = $this->countries->{strtoupper($country_code)}->taxrates;

        // add zero taxrate to array
        if (!in_array(0, $taxrates->reduced)) $taxrates->reduced[] = 0;

        // overwrite taxrates if needed
        $taxrates = $this->check_adjusted_taxrate($country_code, (array) $this->countries->{strtoupper($country_code)}->taxrates, $date);
        if (!$return_unsorted) return $taxrates;

        // unsorted return
        $return = [];
        if (isset($taxrates['default'])) $return[] = $taxrates['default'];
        if (isset($taxrates['reduced']) && count($taxrates['reduced'])) {
            foreach ($taxrates['reduced'] as $reduced) {
                $return[] = $reduced;
            }
        }
        return $return;
    }

    /**
     * Internal function, used to overwrite taxrate for temporary country adjustemnts
     * @param string $country_code  2-letter country code
     * @param array $taxrates       current taxrate array from internal definition
     * @param int $date             booking date timestamp
     * @return array
     */
    private function check_adjusted_taxrate(string $country_code, array $taxrates, int $date): array {

        // german temporary corona tax change (01.07.2020 - 31.12.2020)
        if (strtoupper($country_code) === 'DE' && $date >= 1593554400 && $date <= 1609455599) {
            $taxrates['default'] = 16;
            $taxrates['reduced'] = [5, 0];
        }

        // overwrite until 01.07.2021, no OSS needed so german taxes should give back
        if ($date <= 1625090400 && strtoupper($country_code) != 'DE') return $this->get_taxrates('DE', $date);

        // luxemburg temporary inflation tax change (01.01.2021 - 31.12.2022)
        if (strtoupper($country_code) === 'LU' && $date >= 1672531200 && $date <= 1703977199) {
            $taxrates['default'] = 16;
            $taxrates['reduced'] = [0, 3, 7, 13];
        }

        // spanien temporary inflation tax change (01.01.2021 - 31.12.2022)
        if (strtoupper($country_code) === 'ES' && $date >= 1672531200 && $date <= 1703977199) {
            $taxrates['reduced'][] = 5;
        }

        return $taxrates;
    }

    /**
     * @param float $taxrate used taxrate
     * @param string $country_code 2-letter country code
     * @param int $date booking date timestamp
     * @return bool
     */
    public function check_taxrate(float $taxrate, string $country_code, int $date): bool {
        $taxrates = $this->get_taxrates($country_code, $date);
        if (!empty($taxrates['default']) && $taxrate == $taxrates['default']) return true;

        // iterate because in_array() not like floats for equal check :/
        if (empty($taxrates['reduced'])) return false;
        foreach ($taxrates['reduced'] as $taxrate_reduced) {
            if ($taxrate === $taxrate_reduced) return true;
            if (abs(floatval($taxrate_reduced)-$taxrate) < 0.00001) return true;
        }
        return false;
    }

    /* One Stop Shop (OSS) */
    /**
     * Check if for the current lexoffice settings and given country special OSS-Settings should be used
     * @param string $country_code 2-letter country code from the billing address
     * @param int $date timetsamp booking date
     * @return false|string
     *  bool    false           => no OSS needed, you can proceed without OSS stuff
     *  string  "origin"        => you have to use german taxrates
     *  string  "destination"   => you have to use OSS taxrates
     * @throws \lexoffice_exception
     */
    public function is_oss_needed(string $country_code, int $date) {
        if ($date <= 1625090400) return false; // 01.07.2021

        $profile = $this->get_profile();
        if ($profile->smallBusiness) return false; // not used for taxless businesses
        if ($profile->taxType === 'vatfree') return false; // no taxes in this account
        if ($country_code === strtoupper('DE')) return false; // not for own country
        if (!$this->is_european_member($country_code, $date)) return false; // not for outside EU
        if (empty($profile->distanceSalesPrinciple)) throw new lexoffice_exception('lexoffice-php-api: missing OSS configuration in lexoffice account'); // not configured in lexoffice
        return strtolower($profile->distanceSalesPrinciple);
    }

    /**
     * Return the needed OSS Voucher Booking Category
     * @param string $country_code 2-letter country code
     * @param int $date timestamp booking date
     * @param int $booking_category
     *  1 => Fernverkauf
     *  2 => Elektronische Dienstleistung
     * @param float|int $taxrate
     * @return string lexoffice voucher booking category id
     * @throws \lexoffice_exception
     */
    public function get_oss_voucher_category(string $country_code, int $date, int $booking_category = 1, $taxrate = 0): string {
        $oss_type = $this->is_oss_needed($country_code, $date);
        // german taxrates
        if ($oss_type === 'origin') {
            if ($booking_category === 1) return '7c112b66-0565-479c-bc18-5845e080880a'; // Fernverkauf
            if ($booking_category === 2) return 'd73b880f-c24a-41ea-a862-18d90e1c3d82'; // Elektronische Dienstleistungen
            throw new lexoffice_exception('lexoffice-php-api: invalid given booking_category', ['booking_category' => $booking_category]);
        }
        // target country taxrates
        elseif ($oss_type === 'destination') {
            if ($booking_category === 1) return '4ebd965a-7126-416c-9d8c-a5c9366ee473'; // Fernverkauf in EU-Land steuerpflichtig
            if ($booking_category === 2) return '7ecea006-844c-4c98-a02d-aa3142640dd5'; // Elektronische Dienstleistung in EU-Land steuerpflichtig
            throw new lexoffice_exception('lexoffice-php-api: invalid given booking_category', ['booking_category' => $booking_category]);
        }
        else {
            throw new lexoffice_exception('lexoffice-php-api: no possible OSS voucher category id');
        }
    }

    private function validate_contact_data(array $data): array {
        if (isset($data['company']['name']) && empty($data['company']['name'])) $data['company']['name'] = '-- ohne Firmenname --';
        if (isset($data['person']['firstName']) && empty($data['person']['firstName'])) $data['person']['firstName'] = '-- ohne Vorname --';
        if (isset($data['person']['lastName']) && empty($data['person']['lastName'])) $data['person']['lastName'] = '-- ohne Nachname --';

        // separate multiple phonenumbers in one field
        $phone_numbers_types = ['business', 'office', 'mobile', 'private', 'fax', 'other'];
        $delimiters = ['oder', ','];
        foreach ($phone_numbers_types as $type) {
            if (empty($data['phoneNumbers'][$type])) continue;
            foreach ($data['phoneNumbers'][$type] as $key => $number) {
                $changed = false;
                foreach ($delimiters as $delimiter) {
                    if (stripos($number, $delimiter) === false) continue;
                    $number = strtolower($number);
                    $tmp = explode($delimiter, $number);
                    foreach ($tmp as $tmp_item) {
                        $data['phoneNumbers'][$type][] = trim($tmp_item);
                    }
                    $changed = true;
                }

                // cleanup
                if ($changed) unset($data['phoneNumbers'][$type][$key]);
                $data['phoneNumbers'][$type] = array_unique($data['phoneNumbers'][$type]);
                $data['phoneNumbers'][$type] = array_values($data['phoneNumbers'][$type]);
            }
        }

        // remove chars from numbers
        foreach ($phone_numbers_types as $type) {
            if (empty($data['phoneNumbers'][$type])) continue;
            foreach ($data['phoneNumbers'][$type] as $key => $number) {
                $data['phoneNumbers'][$type][$key] = trim(preg_replace('/[A-Za-z]/', '', $data['phoneNumbers'][$type][$key]));
            }
        }

        // eliminate to long numbers
        foreach ($phone_numbers_types as $type) {
            if (empty($data['phoneNumbers'][$type])) continue;
            foreach ($data['phoneNumbers'][$type] as $key => $number) {
                if (strlen($data['phoneNumbers'][$type][$key] > 30)) unset($data['phoneNumbers'][$type][$key]);
            }
            $data['phoneNumbers'][$type] = array_values($data['phoneNumbers'][$type]);
        }


        // respect lexoffice issue
        // it's only possible to create and change contacts with a
        // maximum of one entry in each lists
        foreach ($phone_numbers_types as $type) {
            if (empty($data['phoneNumbers'][$type]) || count($data['phoneNumbers'][$type]) === 1) continue;
            // only use the first item
            $tmp = $data['phoneNumbers'][$type][0];
            $data['phoneNumbers'][$type] = [];
            $data['phoneNumbers'][$type][] = trim($tmp);
        }


        $email_types = ['business', 'office', 'private', 'other'];
        // remove empty values from nested emailAddresses array
        foreach ($email_types as $type) {
            if (!isset($data['emailAddresses'][$type])) continue;

            foreach ($data['emailAddresses'][$type] as $key => $email) {
                if (empty($email)) unset($data['emailAddresses'][$type][$key]);
            }
            if (count($data['emailAddresses'][$type]) === 0) {
                unset($data['emailAddresses'][$type]);
            }
            else {
                $data['emailAddresses'][$type] = array_values($data['emailAddresses'][$type]);
            }
        }

        // remove empty emailAddresses array
        if (empty($data['emailAddresses'])) unset($data['emailAddresses']);

        // respect lexoffice issue
        // it's only possible to create and change contacts with a
        // maximum of one entry in each lists
        foreach ($email_types as $type) {
            if (empty($data['emailAddresses'][$type]) || count($data['emailAddresses'][$type]) === 1) continue;
            // only use the first item
            $tmp = $data['emailAddresses'][$type][0];
            $data['emailAddresses'][$type] = [];
            $data['emailAddresses'][$type][] = trim($tmp);
        }

        return $data;
    }

    /* legacy wrapper */

    public function get_credit_note($uuid) {
        return $this->get_creditnote($uuid);
    }

    public function create_credit_note($data, $finalized = false) {
        return $this->create_creditnote($data, $finalized);
    }
}

class lexoffice_exception extends Exception {
    private $custom_error;
    public function __construct($message, $data = []) {
        $this->custom_error = $data;
        parent::__construct($message);
    }

    public function get_error() {
        return $this->custom_error;
    }
}