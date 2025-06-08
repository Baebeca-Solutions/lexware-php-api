<?php

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