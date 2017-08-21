<?php
/**
 * mailchimp-api-v3 - [file description]
 * @author adrian7 (adrian@studentmoneysaver.co.uk)
 * @version 1.0
 */

namespace MailChimp;

use Throwable;

/**
 * Class MailChimpAPIException
 * @package MailChimp
 */
class MailChimpAPIException extends \Exception {

    public function __construct( $message = "", $code = 0, Throwable $previous = NULL ) {
        parent::__construct( $message, $code, $previous );
    }

    public function getStatusCode(){
        return $this->code;
    }

}