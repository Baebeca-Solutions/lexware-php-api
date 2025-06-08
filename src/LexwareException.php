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

class LexwareException extends \Exception {
    private $err;

    public function __construct($message, $data = []) {
        $this->err = $data;
        parent::__construct('LexwareApi: '.$message);
    }

    public function getError() {
        return $this->err;
    }
}