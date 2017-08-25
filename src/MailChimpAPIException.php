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

    /**
     * HTTP status code
     * @var int
     */
    protected $status = 0;

    /**
     * Error title
     * @var string
     */
    protected $title;

    /**
     * Error detail
     * @var string
     */
    protected $detail = 'no details';

    /**
     * MailChimpAPIException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|NULL $previous
     */
    public function __construct( $message = "", $code = 0, Throwable $previous = NULL ) {
        parent::__construct( $this->decodeJsonErrorMessage($message), $code, $previous );
    }

    /**
     * HTTP status code
     * @return int
     */
    public function getStatusCode(){
        return $this->status;
    }

    /**
     * Title
     * @return string
     */
    public function getTitle(){
        return $this->title;
    }

    /**
     * Error detail
     * @return string
     */
    public function getDetail(){
        return $this->detail;
    }

    /**
     * Retrieves json error details
     * @param $message
     *
     * @return string
     */
    protected function decodeJsonErrorMessage($message){

        if( $data = @json_decode( strval($message) ) ){

            if( isset($data->title) )
                $this->title = $data->title;

            if( isset($data->status) )
                $this->status = $data->status;

            if( isset($data->detail) )
                $this->detail = $data->detail;

            return ( "#{$this->status} {$this->title} \n {$this->detail}" );
        }

        return $message;

    }

}