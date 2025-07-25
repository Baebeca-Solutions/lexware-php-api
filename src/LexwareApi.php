<?php
/**
 * @package     \baebeca\lexware-php-api
 * @copyright	Baebeca Solutions GmbH
 * @author		Sebastian Hayer-Lutz
 * @email		slu@baebeca.de
 * @link		https://github.com/Baebeca-Solutions/lexware-php-api
 * @license		AGPL-3.0 and Commercial
 * @license 	If you need a commercial license for your closed-source project check: https://github.com/Baebeca-Solutions/lexware-php-api/blob/php-8.4/LICENSE-commercial_EN.md
 **/

namespace Baebeca;

class LexwareApi  {
    protected $api_key = '';
    protected $api_endpoint = 'https://api.lexware.io';
    protected $callback = false;
    protected $ssl_verify = true;
    protected $api_version = 'v1';
    protected $countries;
    private $rate_limit_repeat, $rate_limit_seconds, $rate_limit_max_tries, $rate_limit_callable;

    public function __construct($settings) {
        if (!is_array($settings)) throw new LexwareException('settings should be an array');
        if (empty($settings['api_key'])) throw new LexwareException('no api_key is given');

        $this->api_key = $settings['api_key'];
        if (isset($settings['callback'])) $this->callback = $settings['callback'];
        if (isset($settings['ssl_verify'])) $this->ssl_verify = $settings['ssl_verify'];

        // sandboxes
        if (isset($settings['sandbox']) && $settings['sandbox'] === true) $this->api_endpoint = 'https://api-sandbox.grld.eu';
        if (isset($settings['sandbox_oss']) && $settings['sandbox_oss'] === true) $this->api_endpoint = 'https://api-oss-sandbox.grld.eu';

        $this->configure_rate_limit();
        $this->configure_rate_limit_callable();

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

            // https://www.wko.at/steuern/mehrwertsteuersaetze-eu

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
                    'reduced' => [12],
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
                    'default' => 22,
                    'reduced' => [9, 13],
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
                    'default' => 25.5,
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
                    'default' => 23,
                    'reduced' => [5, 19],
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

    protected function api_call($type, $resource, $uuid = '', $data = '', $params = '', $return_http_header = false, int $count = 1) {
        // check api_key
        if ($this->api_key === true || $this->api_key === false || $this->api_key === '') throw new LexwareException('invalid API Key', ['api_key' => $this->api_key]);

        $ch = curl_init();
        $curl_url = $this->api_endpoint.'/'.$this->api_version.'/'.$resource.'/'.$uuid.$params;

        if ($type == 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

            if ($resource == 'files') {
                $header = ['Authorization: Bearer '.$this->api_key];
                if (!empty($data)) $header[] = 'Accept: '.$data;
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            }
            else {
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer '.$this->api_key,
                    'Accept: application/json',
                ]);
            }

        }
        elseif ($type == 'PUT') {
            $data = json_encode($data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer '.$this->api_key,
                'Content-Type: application/json',
                'Content-Length: '.strlen($data),
                'Accept: application/json',
            ]);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        }
        elseif ($type == 'POST') {
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
        }
        elseif ($type == 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer '.$this->api_key,
                'Accept: application/json',
            ]);
        }
        else {
            throw new LexwareException('unknown request type "'.$type.'" for api_call');
        }

        curl_setopt($ch, CURLOPT_URL, $curl_url);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, $return_http_header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Baebeca Solutions GmbH - lexware-php-api | https://github.com/Baebeca-Solutions/lexware-php-api');

        // skip ssl verify only if manual deactivated (eg. in local tests)
        if (!$this->ssl_verify) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $error = '';

        // prepare data for error message
        if ($data !== '' && is_string($data)) $data = json_decode($data);

        // 200 ok, 201 created, 202 accepted
        if (in_array($http_status, [200, 201, 202])) {
            if (!empty($result) && !($type == 'GET' && $resource == 'files') && !$return_http_header) {
                return json_decode($result);
                // full http_header
            } else if (!empty($result) && $return_http_header) {
                return ['header' => curl_getinfo($ch), 'body' => $result];
                // binary or full http_header
            } else if (!empty($result)) {
                return $result;
            } else {
                $error = 'empty response';
            }
        }
        // ok, but no content
        elseif ($http_status == 204) { return true; }
        elseif ($http_status == 400) { $error = 'Malformed syntax or a bad query'; }
        elseif ($http_status == 401) { $error = 'invalid API Key'; }
        elseif ($http_status == 402) { $error = 'action not possible due a lexware contract issue'; }
        elseif ($http_status == 403) { $error = 'Authenticated but insufficient scope or insufficient access rights in lexware'; }
        elseif ($http_status == 404) { $error = 'Requested resource does no exist (anymore)'; }
        elseif ($http_status == 405) { $error = 'Method not allowed on resource'; }
        elseif ($http_status == 406) { $error = 'Validation issues due to invalid data'; }
        elseif ($http_status == 409) { $error = 'Conflict on ressource'; }
        elseif ($http_status == 415) { $error = 'Missing/Unsupported Content-Type header'; }
        // rate limit, repeat it
        elseif (
            $http_status === 429 &&
            $this->rate_limit_repeat &&
            $count <= $this->rate_limit_max_tries
        ) {
            sleep($this->rate_limit_seconds*$count);
            if (is_callable($this->rate_limit_callable)) call_user_func($this->rate_limit_callable, true);
            return $this->api_call($type, $resource, $uuid, $data, $params, $return_http_header, $count++);
        }
        // rate limit exceeded
        elseif ($http_status === 429) {
            if (is_callable($this->rate_limit_callable)) call_user_func($this->rate_limit_callable, false);
            $error = 'Rate limit exceeded';
        }
        elseif ($http_status == 500) { $error = 'Internal server error'; }
        elseif ($http_status == 501) { $error = 'HTTP operation not supported'; }
        elseif ($http_status == 503) { $error = 'API Service currently unavailable'; }
        elseif ($http_status == 504) { $error = 'API Service Endpoint request timeout'; }

        throw new LexwareException((!empty($error) ? $error : 'error in api request - check details via $e->getError()'), [
            'HTTP Status' => $http_status,
            'Requested Method' => $type,
            'Requested Resource' => $resource,
            'Requested Params' => $params,
            'Requested Data' => $data,
            'Requested URI' => $curl_url,
            'Requested Payload' => $data,
            'Response' => json_decode($result),
        ]);
    }

    public function create_event($event, $callback = false) {
        if (!$callback) $callback = $this->callback;
        if ($callback) {
            return $this->api_call('POST', 'event-subscriptions', '', ['eventType' => $event, 'callbackUrl' => $callback]);
        } else {
            throw new LexwareException('cannot create webhook, no callback given');
        }
    }

    public function create_article(array|object $data) {
        if (is_object($data)) $data = json_decode(json_encode($data, true), true);
        //todo some validation checks
        return $this->api_call('POST', 'articles', '', $data);
    }

    public function get_article($uuid) {
        return $this->api_call('GET', 'articles', $uuid);
    }

    public function get_articles_all() {
        $result = $this->api_call('GET', 'articles', '', '', '?page=0&size=250&direction=ASC&property=name');
        $articles = $result->content;
        unset($result->content);

        for ($i = 1; $i < $result->totalPages; $i++) {
            $result_page = $this->api_call('GET', 'articles', '', '', '?page='.$i.'&size=250&direction=ASC&property=name');
            foreach ($result_page->content as $article) {
                $articles[] = $article;
            }
            unset($result_page->content);
        }
        return($articles);
    }

    public function update_article($uuid, array|object $data) {
        if (is_object($data)) $data = json_decode(json_encode($data, true), true);
        //todo some validation checks
        return $this->api_call('PUT', 'articles', $uuid, $data);
    }

    public function delete_article($uuid) {
        return $this->api_call('DELETE', 'articles', $uuid);
    }

    public function create_contact(array $data) {
        $data = $this->validate_contact_data($data);

        // set version to 0 to create a new contact
        $data['version'] = 0;
        try {
            $new_contact = $this->api_call('POST', 'contacts', '', $data);
        }
        catch (LexwareException $e) {
            $error = $e->getError();
            // try again if new and account_number_already_exists | #188208
            if (isset($error['Response']->IssueList[0]->i18nKey) && $error['Response']->IssueList[0]->i18nKey === 'account_number_already_exists') {
                sleep(3);
                try {
                    $new_contact = $this->api_call('POST', 'contacts', '', $data);
                }
                catch (LexwareException $e) {
                    $error = $e->getError();
                    if (isset($error['Response']->IssueList[0]->i18nKey) && $error['Response']->IssueList[0]->i18nKey === 'account_number_already_exists') {
                        sleep(3);
                        $new_contact = $this->api_call('POST', 'contacts', '', $data);
                    }
                    else throw $e;
                }
            }
            else throw $e;
        }

        // #73917
        // support a technical race condition in lexware database system
        // lexware statement: we have to wait 500ms before we can do anything with the delivered contact id because clustersync need synctime
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

    public function create_orderconfirmation($data, $finalized = false) {
        //todo some validation checks
        return $this->api_call('POST', 'order-confirmations', '', $data, ($finalized ? '?finalize=true' : ''));
    }

    public function create_voucher($data) {
        return $this->api_call('POST', 'vouchers', '', $data);
    }

    public function create_delivery_note($data, $finalized = false) {
        return $this->api_call('POST', 'delivery-notes', '', $data, ($finalized ? '?finalize=true' : ''));
    }

    public function create_dunning($data, $precedingSalesVoucherId) {
        return $this->api_call('POST', 'dunnings', '', $data, ($precedingSalesVoucherId ? '?precedingSalesVoucherId='.$precedingSalesVoucherId : ''));
    }

    public function get_event($uuid) {
        return $this->api_call('GET', 'event-subscriptions', $uuid);
    }

    public function get_events_all() {
        return $this->api_call('GET', 'event-subscriptions');
    }

    public function get_recurring_template($uuid) {
        return $this->api_call('GET', 'recurring-templates', $uuid);
    }

    public function get_recurring_templates_all() {
        $result = $this->api_call('GET', 'recurring-templates', '', '', '?page=0&size=250&sort=createdDate,ASC');
        $recurring_templates = $result->content;
        unset($result->content);

        for ($i = 1; $i < $result->totalPages; $i++) {
            $result_page = $this->api_call('GET', 'recurring-templates', '', '', '?page='.$i.'&size=250&sort=createdDate,ASC');
            foreach ($result_page->content as $recurring_template) {
                $recurring_templates[] = $recurring_template;
            }
            unset($result_page->content);
        }
        return($recurring_templates);
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
        if ($count <= 0) throw new LexwareException('positive invoice count needed');

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

    public function get_dunning($uuid) {
        return $this->api_call('GET', 'dunnings', $uuid);
    }

    /**
    *  @deprecated use get_pdf($type, $uuid, $filename) instead / will be removed in futere releases
    */
    public function get_invoice_pdf($uuid, $filename) {
        // check if invoice is a draft
        $invoice = $this->get_invoice($uuid);
        if ($invoice->voucherStatus == 'draft') throw new LexwareException('requested invoice is a draft. Cannot create/download pdf file. Check details via $e->getError()', ['invoice_id' => $uuid]);

        return $this->get_pdf('invoices', $uuid, $filename);
    }

    public function get_pdf($type, $uuid, $filename): bool {
        if ($type === 'downpaymentinvoice') $type = 'down-payment-invoices';
        if ($type === 'dunning') $type = 'dunnings';
        $request = $this->api_call('GET', $type, $uuid);

        // no PDFs for drafts, except dunnings
        if ($request->voucherStatus === 'draft' && $type !== 'dunnings') return false;

        // document already exists
        if (!empty($request->files->documentFileId)) {
            $documentFileId = $request->files->documentFileId;
        }
        // document rendering needed
        else {
            $request = $this->api_call('GET', $type, $uuid, '', '/document');
            if (empty($request->documentFileId)) return false;
            $documentFileId = $request->documentFileId;
        }

        // download pdf
        $request_file = $this->api_call('GET', 'files', $documentFileId);
        if (!$request_file) return false;
        file_put_contents($filename, $request_file);

        // check additonal X-Rechnung XML
        if (!empty($request->electronicDocumentProfile) && $request->electronicDocumentProfile === 'XRechnung') {
            $request_file = $this->api_call('GET', 'files', $documentFileId, 'application/xml');
            if ($request_file) file_put_contents($filename.'.xml', $request_file);
        }
        return true;
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

        // #69724 - warning - lexware::init::vouchers
        // at the moment it is not possible to request more than 10K items due lexware internal restrictions
        // the lexware-API will throw an HTTP 500, so lets abort it until lexware has integrated a solution for this limitation
        // check it here: https://github.com/Baebeca-Solutions/lexware-php-api/issues/31
        if ($result->totalPages >= 40) { // 40 pages * 250 items == 10k
            // we have to split it up in smaller requests
            // lets start again
            $result = [];

            if (empty($date_from)) $date_from = '2011-01-01'; // set start day to lexware deployment date

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
        if (!$voucher || !isset($voucher->files[0])) throw new LexwareException('voucher has no files. Cannot download file. Check details via $e->getError()', ['voucher_id' => $uuid]);

        // iterate files
        $i = 1;
        $saved_files = [];
        foreach ($voucher->files as $uuid_file) {
            $xRechnung = false;
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
                case 'application/xml':
                case 'text/xml':
                    $extension = 'xml';
                    $xRechnung = true;
                    break;
                default:
                    throw new LexwareException('unknown mime/type "'.$request['header']['content_type'].'". Check details via $e->getError()', ['voucher_id' => $uuid, 'response' => $request]);
            }

            $filename = $filename_prefix.'_'.$i.'.'.$extension;
            file_put_contents($filename, $body);
            $saved_files[] = $filename;

            // get additional visual files
            if ($xRechnung) {
                $request = $this->api_call('GET', 'files', $uuid_file, 'application/pdf', '', true);
                $body = substr($request['body'], $request['header']['header_size']);
                $filename = $filename_prefix.'_'.$i.'.pdf';
                file_put_contents($filename, $body);
                $saved_files[] = $filename;
            }

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

    public function update_contact($uuid, array|object $data) {
        if (is_object($data)) $data = json_decode(json_encode($data, true), true);
        $data = $this->validate_contact_data($data);
        return $this->api_call('PUT', 'contacts', $uuid, $data);
    }

    // todo
    #public function update_invoice() {
    #
    #}

    public function delete_event($uuid) {
        return $this->api_call('DELETE', 'event-subscriptions', $uuid);
    }

    public function search_contact(array $filters, bool $wildcards = false) {
        // todo integrate pagination

        $filter_string = '';
        foreach ($filters as $index => $filter) {
            if (empty($filter)) continue;

            // escape wildcards if needed
            if (!$wildcards) {
                // respect versions that are already encoded
                if ($filter === rawurldecode($filter)) {
                    $filter = str_replace(
                        ['_', '%'],
                        ['\_', '\%'],
                        $filter
                    );
                }
                else {
                    $filter = str_replace(
                        ['_', '%'],
                        ['\_', '\%'],
                        rawurldecode($filter)
                    );
                    if ($filter === htmlspecialchars_decode($filter, ENT_NOQUOTES)) {
                        $filter = htmlspecialchars($filter, ENT_NOQUOTES);
                    }
                    $filter = rawurlencode($filter);
                }
            }

            // check if is not already encoded
            if (strpos($filter, '%') === false || $filter == rawurldecode($filter)) {
                if ($filter === htmlspecialchars_decode($filter, ENT_NOQUOTES)) {
                    $filter = htmlspecialchars($filter, ENT_NOQUOTES);
                }
                $filter = rawurlencode($filter);
            }
            //if urlencoded, but not html
            elseif (rawurldecode($filter) == htmlspecialchars_decode(rawurldecode($filter), ENT_NOQUOTES)) {
                $filter = rawurlencode(htmlspecialchars(rawurldecode($filter), ENT_NOQUOTES));
            }

            // replace spacer | sometimes on appended lastname which is already encoded and skipped above
            $filter = str_replace(' ', '%20', $filter);

            if (($index == 'customer' || $index == 'vendor') && $filter !== '') {
                // bool to text
                if ($filter === true) $filter = 'true';
                if ($filter === false) $filter = 'false';
                $filter_string.= $index.'='.$filter.'&';
            } elseif (($index == 'email' || $index == 'name') && $filter !== '') {
                if (strlen($filter) < 3) throw new LexwareException('search pattern must have least 3 characters');
                $filter_string.= $index.'='.$filter.'&';
            } elseif ($filter !== '') {
                $filter_string.= $index.'='.$filter.'&';
            }
        }

        if (!$filter_string) throw new LexwareException('no valid filter for searching contacts');
        return $this->api_call('GET', 'contacts', '', '', '?'.substr($filter_string, 0, -1));
    }

    // todo check lifetime api key

    public function upload_file($file) {
        if (!file_exists($file)) throw new LexwareException('file does not exist', ['file' => $file]);
        if (filesize($file) > 5*1024*1024) throw new LexwareException('filesize to big', ['file' => $file, 'filesize' => filesize($file).' byte']);
        // use mimetype type from filename
        if (
            in_array(substr(strtolower($file), -4), ['.pdf', '.jpg', '.png', '.xml']) ||
            in_array(substr(strtolower($file), -5), ['.jpeg'])
        ) {
            return $this->api_call('POST', 'files', '', ['file' => new \CURLFile($file), 'type' => 'voucher']);
        }

        // use file mimetype (lexware requires a fileextension in the filename :/)
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
            case 'application/xml':
            case 'text/xml':
                $dummy_title = 'dummy.xml';
                break;
            default:
                throw new LexwareException('invalid mime type', ['file' => $file]);
        }
        return $this->api_call('POST', 'files', '', ['file' => new \CURLFile($file, $mime_type, $dummy_title), 'type' => 'voucher']);
    }

    public function upload_voucher($uuid, $file) {
        if (!file_exists($file)) throw new LexwareException('file does not exist', ['file' => $file]);
        if (filesize($file) > 5*1024*1024) throw new LexwareException('filesize to big', ['file' => $file, 'filesize' => filesize($file).' byte']);
        // use mimetype type from filename
        if (
            in_array(substr(strtolower($file), -4), ['.pdf', '.jpg', '.png', '.xml']) ||
            in_array(substr(strtolower($file), -5), ['.jpeg'])
        ) {
            return $this->api_call('POST', 'vouchers', $uuid, ['file' => new \CURLFile($file)], '/files');
        }

        // use file mimetype (lexware requires a fileextension in the filename :/)
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
            case 'application/xml':
            case 'text/xml':
                $dummy_title = 'dummy.xml';
                break;
            default:
                throw new LexwareException('invalid mime type', ['file' => $file]);
        }
        return $this->api_call('POST', 'vouchers', $uuid, ['file' => new \CURLFile($file, $mime_type, $dummy_title), 'type' => 'voucher'], '/files');
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
     * @throws \LexwareException
     */
    public function get_needed_voucher_booking_id(float $taxrate, string $country_code, int $date, bool $euopean_vatid, bool $b2b_business, bool $physical_good = true): string {
        $country_code = strtoupper($country_code);

        // Weltweit, Kleinunternehmer
        if ($this->is_tax_free_company() && $taxrate) throw new LexwareException('invalid taxrate for taxfree company');
        if ($this->is_tax_free_company()) return '7a1efa0e-6283-4cbf-9583-8e88d3ba5960'; // §19 Kleinunternehmer

        // Deutschland
        if ($country_code === 'DE') {
            if (!$this->check_taxrate($taxrate, $country_code, $date)) throw new LexwareException('invalid taxrate for given country', ['taxrate' => $taxrate, 'country_code' => $country_code, 'date' => $date]);
            if ($physical_good) return '8f8664a8-fd86-11e1-a21f-0800200c9a66'; // Einnahmen -> Warenlieferung
            return '8f8664a0-fd86-11e1-a21f-0800200c9a66'; // Einnahmen
        }

        // Europa
        if ($this->is_european_member($country_code, $date)) {
            // B2B - Warenlieferung
            if (!$taxrate && $euopean_vatid && $b2b_business && $physical_good) return '9075a4e3-66de-4795-a016-3889feca0d20'; // Innergemeinschaftliche Lieferung
            // B2B - Dienstleistung
            if (!$taxrate && $euopean_vatid && $b2b_business && !$physical_good) return '380a20cb-d04c-426e-b49c-84c22adfa362'; // Fremdleistungen §13b

            // Check OSS Stuff
            $oss = $this->is_oss_needed($country_code, $date);

            // Europa B2C
            // Waren und Dienstleistungen ohne OSS
            if ($oss === false) return '8f8664a1-fd86-11e1-a21f-0800200c9a66'; // Einnahmen

            // oss "destination" configuration (we have to check with target country taxrates)
            if ($oss === 'destination') {
                // check oss tax rate
                $oss_taxrates = $this->get_taxrates($country_code, $date);

                if (empty($oss_taxrates)) throw new LexwareException('unknown OSS booking scenario, cannot decide correct taxrates', [
                    'taxrate' => $taxrate,
                    'country_code' => $country_code,
                    'european_vatid' => $euopean_vatid,
                    'b2b_business' => $b2b_business,
                    'physical_good' => $physical_good,
                ]);

                if (!$this->check_taxrate($taxrate, $country_code, $date)) throw new LexwareException('invalid OSS taxrate for given country', [
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

                if (empty($oss_taxrates)) throw new LexwareException('unknown OSS booking scenario, cannot decide correct taxrates', [
                    'taxrate' => $taxrate,
                    'country_code' => 'DE',
                    'european_vatid' => $euopean_vatid,
                    'b2b_business' => $b2b_business,
                    'physical_good' => $physical_good,
                ]);

                if (!$this->check_taxrate($taxrate, 'DE', $date)) throw new LexwareException('invalid OSS taxrate for given country', [
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

            throw new LexwareException('unknown OSS configuration', [
                'oss' => $oss,
            ]);
        }

        // Welt (inkl. Schweiz) - Warenlieferung
        if ($physical_good) {
            // B2B
            if ($taxrate == 0 && $b2b_business) return '93d24c20-ea84-424e-a731-5e1b78d1e6a9'; // Ausfuhrlieferungen an Drittländer
            // B2C
            if ($taxrate == 0 && !$b2b_business) return '8f8664a1-fd86-11e1-a21f-0800200c9a66'; // Einnahmen

            throw new LexwareException('unknown booking scenario, world shipping with taxes. cannot decide correct booking category', [
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
            if ($taxrate == 0 && !$b2b_business) return '8f8664a1-fd86-11e1-a21f-0800200c9a66'; // Einnahmen | #87248 - API - individual_person_not_applicable_service_third_party (lexware does not support the correct booking type)

            // Welt (inkl. Schweiz) - B2C
            #if ($taxrate > 0) return '8f8664a1-fd86-11e1-a21f-0800200c9a66'; // Einnahmen

            throw new LexwareException('unknown booking scenario, world service with taxes. cannot decide correct booking category', [
                'taxrate' => $taxrate,
                'country_code' => $country_code,
                'date' => $date,
                'european_vatid' => $euopean_vatid,
                'b2b_business' => $b2b_business,
                'physical_good' => $physical_good,
            ]);
        }

        throw new LexwareException('unknown booking scenario, cannot decide correct booking category', [
            'taxrate' => $taxrate,
            'country_code' => $country_code,
            'date' => $date,
            'european_vatid' => $euopean_vatid,
            'b2b_business' => $b2b_business,
            'physical_good' => $physical_good,
        ]);
    }

    public function get_needed_tax_type(string $customer_country_code, string $vat_id, bool $physical_good, int $timestamp): string {
        if (strtoupper($customer_country_code) === 'DE') return 'net';
        if (!empty($vat_id) && $this->is_european_member($customer_country_code, $timestamp)) return 'intraCommunitySupply';
        if (!$this->is_european_member($customer_country_code, $timestamp)) {
            if ($physical_good) return 'thirdPartyCountryDelivery';
            return 'thirdPartyCountryService';
        }
        return 'net';
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

        // luxemburg temporary inflation tax change (01.01.2021 - 31.12.2023)
        if (strtoupper($country_code) === 'LU' && $date >= 1672531200 && $date <= 1703977199) {
            $taxrates['default'] = 16;
            $taxrates['reduced'] = [0, 3, 7, 13];
        }

        // spanien temporary inflation tax change (01.01.2021 - 31.12.2022)
        if (strtoupper($country_code) === 'ES' && $date >= 1672531200 && $date <= 1703977199) {
            $taxrates['reduced'][] = 5;
        }

        // Slowakische Republik old tax before 01.01.2025
        if (strtoupper($country_code) === 'SK' && $date <= 1735685999) {
            $taxrates['default'] = 20;
            $taxrates['reduced'] = [0, 5, 10];
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
        if ($taxrate && $this->is_tax_free_company()) return false;

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
     * Check if for the current lexware settings and given country special OSS-Settings should be used
     * @param string $country_code 2-letter country code from the billing address
     * @param int $date timetsamp booking date
     * @return false|string
     *  bool    false           => no OSS needed, you can proceed without OSS stuff
     *  string  "origin"        => you have to use german taxrates
     *  string  "destination"   => you have to use OSS taxrates
     * @throws \LexwareException
     */
    public function is_oss_needed(string $country_code, int $date) {
        if ($date <= 1625090400) return false; // 01.07.2021

        $profile = $this->get_profile();
        if ($profile->smallBusiness) return false; // not used for taxless businesses
        if ($profile->taxType === 'vatfree') return false; // no taxes in this account
        if ($country_code === strtoupper('DE')) return false; // not for own country
        if (!$this->is_european_member($country_code, $date)) return false; // not for outside EU
        if (empty($profile->distanceSalesPrinciple)) throw new LexwareException('missing OSS configuration in lexware account'); // not configured in lexware
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
     * @return string lexware voucher booking category id
     * @throws \LexwareException
     */
    public function get_oss_voucher_category(string $country_code, int $date, int $booking_category = 1, $taxrate = 0): string {
        $oss_type = $this->is_oss_needed($country_code, $date);
        // german taxrates
        if ($oss_type === 'origin') {
            if ($booking_category === 1) return '7c112b66-0565-479c-bc18-5845e080880a'; // Fernverkauf
            if ($booking_category === 2) return 'd73b880f-c24a-41ea-a862-18d90e1c3d82'; // Elektronische Dienstleistungen
            throw new LexwareException('invalid given booking_category', ['booking_category' => $booking_category]);
        }
        // target country taxrates
        elseif ($oss_type === 'destination') {
            if ($booking_category === 1) return '4ebd965a-7126-416c-9d8c-a5c9366ee473'; // Fernverkauf in EU-Land steuerpflichtig
            if ($booking_category === 2) return '7ecea006-844c-4c98-a02d-aa3142640dd5'; // Elektronische Dienstleistung in EU-Land steuerpflichtig
            throw new LexwareException('invalid given booking_category', ['booking_category' => $booking_category]);
        }
        else {
            throw new LexwareException('no possible OSS voucher category id');
        }
    }

    public function valid_vat_id($vat_id) {
        $vat_id = strtoupper(trim($vat_id));
        $country_chars = substr($vat_id, 0, 2);
        $vat_id_length = strlen($vat_id);

        return (
            $country_chars === 'GB' && $vat_id_length === 7 ||
            in_array($country_chars, array('CZ', 'DK', 'FI', 'HU', 'IE', 'LU', 'MT', 'SI')) && $vat_id_length === 10 ||
            in_array($country_chars, array('AT', 'BG', 'CY', 'CZ', 'DE', 'EE', 'EL', 'ES', 'GB', 'IE', 'LT', 'PT', 'RO')) && $vat_id_length === 11 ||
            in_array($country_chars, array('BE', 'BG', 'CZ', 'PL', 'SK')) && $vat_id_length === 12 ||
            in_array($country_chars, array('FR', 'HR', 'IT', 'LV')) && $vat_id_length === 13 ||
            in_array($country_chars, array('GB', 'LT', 'NL', 'SE')) && $vat_id_length === 14
        );
    }

    private function validate_contact_data(array $data): array {
        if (isset($data['company']['name']) && empty($data['company']['name'])) $data['company']['name'] = '-- ohne Firmenname --';
        if (isset($data['person']['firstName']) && empty($data['person']['firstName'])) $data['person']['firstName'] = '-- ohne Vorname --';
        if (isset($data['person']['lastName']) && empty($data['person']['lastName'])) $data['person']['lastName'] = '-- ohne Nachname --';
        if (!empty($data['company']['vatRegistrationId']) && !$this->valid_vat_id($data['company']['vatRegistrationId'])) unset($data['company']['vatRegistrationId']);

        // fix to long salutations
        if (!empty($data['person']['salutation']) && strlen($data['person']['salutation'] > 25)) {
            if (str_contains($data['person']['salutation'], 'Frau')) {
                $data['person']['salutation'] = 'Frau';
            }
            elseif (str_contains($data['person']['salutation'], 'Herr')) {
                $data['person']['salutation'] = 'Herr';
            }
            else {
                $data['person']['salutation'] = substr($data['person']['salutation'], 0, 25);
            }
        }

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
                if (strlen($data['phoneNumbers'][$type][$key]) > 30) unset($data['phoneNumbers'][$type][$key]);
            }
            $data['phoneNumbers'][$type] = array_values($data['phoneNumbers'][$type]);
        }

        // remove empty numbers
        foreach ($phone_numbers_types as $type) {
            if (empty($data['phoneNumbers'][$type])) continue;
            foreach ($data['phoneNumbers'][$type] as $key => $number) {
                if (empty($data['phoneNumbers'][$type][$key])) unset($data['phoneNumbers'][$type][$key]);
            }
            $data['phoneNumbers'][$type] = array_values($data['phoneNumbers'][$type]);
        }

        // respect lexware issue
        // it's only possible to create and change contacts with a
        // maximum of one entry in each lists
        foreach ($phone_numbers_types as $type) {
            if (empty($data['phoneNumbers'][$type]) || count($data['phoneNumbers'][$type]) === 1) continue;
            // only use the first item
            $tmp = $data['phoneNumbers'][$type][0];
            $data['phoneNumbers'][$type] = [];
            $data['phoneNumbers'][$type][] = trim($tmp);
        }

        // validation for contactPersons in company
        if (!empty($data['company']['contactPersons'])) {
            foreach ($data['company']['contactPersons'] as $key => $person) {
                // fix to long salutations
                if (!empty($person['salutation']) && strlen($person['salutation'] > 25)) {
                    if (str_contains($person['salutation'], 'Frau')) {
                        $data['company']['contactPersons'][$key]['salutation'] = 'Frau';
                    }
                    elseif (str_contains($person['salutation'], 'Herr')) {
                        $data['company']['contactPersons'][$key]['salutation'] = 'Herr';
                    }
                    else {
                        $data['company']['contactPersons'][$key]['salutation'] = substr($person['salutation'], 0, 25);
                    }
                }
                if (empty($person['firstName'])) $data['company']['contactPersons'][$key]['firstName'] = '-- ohne Vorname --';
                if (empty($person['lastName'])) $data['company']['contactPersons'][$key]['lastName'] = '-- ohne Nachname --';
            }
        }

        // validation for contactPersons number in company
        if (isset($data['company']['contactPersons'][0]['phoneNumber'])) {
            $data['company']['contactPersons'][0]['phoneNumber'] = trim(preg_replace('/[A-Za-z]/', '', $data['company']['contactPersons'][0]['phoneNumber']));
            //if delimeters - leave first correct number
            foreach ($delimiters as $delimiter) {
                if (stripos($data['company']['contactPersons'][0]['phoneNumber'], $delimiter) === false) continue;
                $tmp = explode($delimiter, $data['company']['contactPersons'][0]['phoneNumber']);
                foreach ($tmp as $tmp_item) {
                    if (empty($tmp_item)) continue;
                    $data['company']['contactPersons'][0]['phoneNumber'] = trim($tmp_item);
                    break;
                }
            }
            if (empty($data['company']['contactPersons'][0]['phoneNumber']) || strlen($data['company']['contactPersons'][0]['phoneNumber']) > 30)
                unset($data['company']['contactPersons'][0]['phoneNumber']);
        }

        if (isset($data['company']['contactPersons'][0]['emailAddress'])) {
            // only to lower, dont remove invalid emails! process should be stopped with error, otherwise search for this contact will be negative
            $data['company']['contactPersons'][0]['emailAddress'] = mb_strtolower($data['company']['contactPersons'][0]['emailAddress']);
        }

        $email_types = ['business', 'office', 'private', 'other'];
        // remove empty values from nested emailAddresses array
        foreach ($email_types as $type) {
            if (!isset($data['emailAddresses'][$type])) continue;

            foreach ($data['emailAddresses'][$type] as $key => $email) {
                // only to lower, dont remove invalid emails! process should be stopped with error, otherwise search for this contact will be negative
                $data['emailAddresses'][$type][$key] = mb_strtolower($data['emailAddresses'][$type][$key]);
                // remove empty mails, because lexware will decline the request
                if ($email === '') unset($data['emailAddresses'][$type][$key]);
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

        // respect lexware issue
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

    public function configure_rate_limit(bool $repeat = true, int $seconds_to_sleep = 1, int $max_tries = 10) : void {
        $this->rate_limit_repeat = $repeat;
        $this->rate_limit_seconds = $seconds_to_sleep;
        $this->rate_limit_max_tries = $max_tries;
    }

    public function configure_rate_limit_callable(?callable $callback = null) : void {
        $this->rate_limit_callable = $callback;
    }

    /* legacy wrapper */

    public function get_credit_note($uuid) {
        return $this->get_creditnote($uuid);
    }

    public function create_credit_note($data, $finalized = false) {
        return $this->create_creditnote($data, $finalized);
    }

    public function test_set_profile($taxType = 'net', $smallBusiness = false, $distanceSalesPrinciple = 'ORIGIN') {
        $profile = [
            'organizationId' => null,
            'companyName' => 'Testname',
            'created' => [
                'userId' => null,
                'userName' => null,
                'userEmail' => null,
                'date' => null
            ],
            'connectionId' => null,
            'features' => [],
            'businessFeatures' => [],
            'subscriptionStatus' => 'active',
            'taxType' => $taxType,
            'smallBusiness' => $smallBusiness,
            'distanceSalesPrinciple' => $distanceSalesPrinciple
        ];
        $this->cache_profile = (object) $profile;
    }

    public function test_clear_profile() {
        $this->cache_profile = null;
    }
}