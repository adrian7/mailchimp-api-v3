<?php

namespace MailChimp;

use Exception;
use GuzzleHttp\Client;
use BadMethodCallException;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

/**
 * Class MailChimpAPI
 * @package MailChimp
 *
 * @method mixed get($resource, array $options = [])
 * @method mixed head($resource, array $options = [])
 * @method mixed put($resource, array $options = [])
 * @method mixed post($resource, array $options = [])
 * @method mixed patch($resource, array $options = [])
 * @method mixed delete($resource, array $options = [])
 */
class MailChimpAPI {

    /**
     * Endpoint for Mailchimp API v3
     *
     * @var string
     */
    private $endpoint = 'https://us1.api.mailchimp.com/3.0/';

    /**
     * @var string
     */
    private $apikey;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $allowedMethods = ['get', 'head', 'put', 'post', 'patch', 'delete'];

    /**
     * @var array
     */
    public $options = [];

    /**
     * @param string $apikey
     * @param array $clientOptions
     */
    public function __construct($apikey = '', $clientOptions = []) {

        $this->apikey = $apikey;
        $this->client = new Client($clientOptions);

        $this->detectEndpoint($this->apikey);

        $this->options['headers'] = [
            'Authorization' => 'apikey ' . $this->apikey
        ];

    }

    /**
     * @param string $resource
     * @param array $arguments
     * @param string $method
     * @return mixed
     * @throws Exception
     */
    public function request($resource, $arguments = [], $method = 'GET') {

        if ( ! $this->apikey )
            throw new MailChimpAPIException('Please provide an API key.');

        return $this->makeRequest($resource, $arguments, strtolower($method));

    }

    /**
     * Enable proxy if needed.
     *
     * @param string $host
     * @param int $port
     * @param bool $ssl
     * @param string $username
     * @param string $password
     * @return string
     */
    public function setProxy(
        $host,
        $port,
        $ssl = false,
        $username = null,
        $password = null
    ) {

        $scheme = ( $ssl ? 'https://' : 'http://' );

        if ( ! is_null($username)) {
            return $this->options['proxy'] = sprintf('%s%s:%s@%s:%s', $scheme, $username, $password, $host, $port);
        }

        return $this->options['proxy'] = sprintf('%s%s:%s', $scheme, $host, $port);

    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param $apikey
     */
    public function detectEndpoint($apikey)
    {
        if ( ! strstr($apikey, '-')) {
            throw new InvalidArgumentException('There seems to be an issue with your apikey. Please consult Mailchimp');
        }

        list(, $dc) = explode('-', $apikey);
        $this->endpoint = str_replace('us1', $dc, $this->endpoint);
    }

    /**
     * @param string $apikey
     */
    public function setApiKey($apikey)
    {
        $this->detectEndpoint($apikey);

        $this->apikey = $apikey;
    }

    /**
     * @param string $resource
     * @param array $arguments
     * @param string $method
     * @return object|array
     * @throws Exception
     */
    private function makeRequest($resource, $arguments, $method) {

        try {

            $options  = $this->getOptions($method, $arguments);
            $response = $this->client->{$method}(
                ( $this->endpoint . $resource ),
                $options
            );

            //plain old json_decode
            $data = ( 204 == $response->getStatusCode() ) ? TRUE : json_decode( $response->getBody() );

            if( empty($data) and ( $de = json_last_error() ) )
                throw new MailChimpAPIException(
                    "Could not decode API response... . 
                    JSON error #{$de}: " . json_last_error_msg()
                );

            return $data;

        } catch (ClientException $e) {
            throw new MailChimpAPIException(
                $e->getResponse()->getBody(),
                $e->getResponse()->getStatusCode(),
                $e
            );

        } catch (RequestException $e) {

            $response = $e->getResponse();

            if ( $response instanceof ResponseInterface )
                throw new MailChimpAPIException(
                    $e->getResponse()->getBody(),
                    $e->getResponse()->getStatusCode(),
                    $e
                );


            throw new MailChimpAPIException( $e->getMessage() );

        }

    }

    /**
     * @param string $method
     * @param array $arguments
     * @return array
     */
    private function getOptions($method, $arguments) {

        unset($this->options['json'], $this->options['query']);

        if (count($arguments) < 1) {
            return $this->options;
        }

        if ($method == 'get') {
            $this->options['query'] = $arguments;
        } else {
            $this->options['json'] = $arguments;
        }

        return $this->options;
    }

    /**
     * @param string $method
     * @param array $arguments
     * @return object|array
     * @throws Exception
     */
    public function __call($method, $arguments) {

        if ( count($arguments) < 1 )
            throw new InvalidArgumentException(
                'Magic request methods require a URI and optional options array'
            );

        if ( ! in_array($method, $this->allowedMethods) )
            throw new BadMethodCallException(
                'Method "' . $method . '" is not supported.'
            );

        $resource = $arguments[0];
        $options  = isset($arguments[1]) ? $arguments[1] : [];

        return $this->request($resource, $options, $method);

    }

}
