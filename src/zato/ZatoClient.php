<?php

namespace zato;

use \GuzzleHttp\Client;
use \zato\Resources\Core\Ping;
use \zato\Resources\Services\Invoke;

/**
 * Class zato_Client
 */
class ZatoClient
{
    const VERSION = '0.1';


    /**
     * @var array $headers
     */
    private $headers = [];

    /**
     * @var string
     */
    protected $user;

    /**
     * @var string
     */
    protected $pass;

    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var string
     */
    protected $hostname;

    /**
     * @var integer
     */
    protected $port;

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @var Debug
     */
    protected $debug;

    /**
     * @var \GuzzleHttp\Client
     */
    public $guzzle;

    /**
     * @var array
     */
    private $_config;

    public function __construct($config = array(), $client_opts = array())
    {
        $this->_config = array_merge(
            [
                'scheme' => 'http',
                'user' => 'pubapi',
                'pass' => 'default',
                'hostname' => 'localhost',
                'port' => 11223,
                'api_base' => 'zato/',
            ],
            $config
        );

        $this->scheme    = $this->_config['scheme'];
        $this->user      = $this->_config['user'];
        $this->pass      = $this->_config['pass'];
        $this->hostname  = $this->_config['hostname'];
        $this->port      = $this->_config['port'];
        $this->_apiUrl   = "{$this->scheme}://{$this->hostname}:{$this->port}/{$this->_config['api_base']}";

        $guzzOptions = array_merge([
            'base_uri' => $this->_apiUrl,
            'auth' => array($this->user, $this->pass)
        ], $client_opts);

        $this->guzzle = new Client($guzzOptions);
        $this->debug  = new Debug();
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Returns the generated api URL
     *
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * Return the user agent string
     *
     * @return string
     */
    public function getUserAgent()
    {
        return 'zato PHP client ' . self::VERSION;
    }

    /**
     * Set debug information as an object
     *
     * @param mixed  $lastRequestHeaders
     * @param mixed  $lastRequestBody
     * @param mixed  $lastResponseCode
     * @param string $lastResponseHeaders
     * @param mixed  $lastResponseError
     */
    public function setDebug(
        $lastRequestHeaders,
        $lastRequestBody,
        $lastResponseCode,
        $lastResponseHeaders,
        $lastResponseError
    ) {
        $this->debug->lastRequestHeaders  = $lastRequestHeaders;
        $this->debug->lastRequestBody     = $lastRequestBody;
        $this->debug->lastResponseCode    = $lastResponseCode;
        $this->debug->lastResponseHeaders = $lastResponseHeaders;
        $this->debug->lastResponseError   = $lastResponseError;
    }

    /**
     * Returns debug information from last call
     * in an object
     *
     * @return Debug
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * This is a helper method to do a post request.
     *
     * @param string $endpoint
     * @param array $postData
     *
     * @return stdClass
     * @throws ApiResponseException
     */
    public function post($endpoint, $postData = [])
    {
        $response = Http::send(
            $this,
            $endpoint,
            [
                'postFields' => $postData,
                'method'     => 'POST'
            ]
        );
        return $response;
    }

    /**
     * A ping service which always returns a constant string. Useful for testing clients against zato clusters.
     *
     * @return stdClass Object that has the same properties as the corresponding zato response
     */
    public function ping()
    {
        $endpoint = new Ping($this);
        return $endpoint->execute();
    }

    /**
     * Invokes a service by its ID or name. From the service’s viewpoint, there is no difference between being invoked
     * directly, through a channel or if using zato.service.invoke.
     *
     * If executing a service in async mode, its response will be a CID it’s been invoked with.
     *
     * Client configuration settings include the following options:
     *
     *
     * - id	            int	        Service ID. Either id or name must be provided.
     * - name	        string	    Service name. Either id or name must be provided.
     * - payload		string      Data to be used as input by the zato service, can be any PHP type except a resource.
     * - channel	    string	    Channel the service will believe it’s being invoked over
     * - data_format	string	    Payload’s data format (json set as default)
     * - transport	    string	    Transport the service should believe it’s being invoked with
     * - async	        boolean	    Whether the service should be invoked asynchronously, defaults to False
     * - expiration	    int	    	If using async mode, after how many seconds the message should expire, defaults to 15 seconds
     * - endpoint       string      if using a custom channel the endpoint can be specified here, if no endpoint specified it will set the pubapi one
     *
     * @param array $params
     * @return stdClass Decoded payload object from service invoke
     */
    public function serviceInvoke($params)
    {
        $endpoint = new Invoke($this);
        return $endpoint->execute($params);
    }
}