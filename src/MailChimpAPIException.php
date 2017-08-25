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
        parent::__construct( $this->decodeJsonErrorMessage($message), $code, $previous );
    }

    public function getStatusCode(){
        return $this->code;
    }

    protected function decodeJsonErrorMessage($message){

        if( is_object($message) or is_array($message) ); else{
            $message = ( $data = @json_decode($message) ) ? $data : $message;
        }

        if( is_string($message) )
            return $message;

        return json_encode($message, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    }

}