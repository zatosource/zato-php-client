<?php

namespace Zato\API;

use \GuzzleHttp\Client;
use \Zato\API\Resources\Core\Ping;
use \Zato\API\Resources\Services\Invoke;

/**
 * Class Zato_Client
 */
class HttpClient
{
    const VERSION = '0.1';


    /**
     * @var array $headers
     */
    private $headers = [];

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var string
     */
    protected $username;

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

    public function __construct($config = array())
    {
        $this->_config = array_merge(
            [
                'scheme' => 'http',
                'hostname' => 'localhost',
                'port' => 11223,
                'api_base' => 'zato/',
            ],
            $config
        );

        $this->user      = $this->_config['user'];
        $this->pass      = $this->_config['pass'];
        $this->hostname  = $this->_config['hostname'];
        $this->scheme    = $this->_config['scheme'];
        $this->port      = $this->_config['port'];

        $this->_apiUrl = "{$this->scheme}://{$this->hostname}:{$this->port}/{$this->_config['api_base']}";
        $this->guzzle = new Client([
            'base_uri' => $this->_apiUrl,
            'auth' => array($this->user, $this->pass)
        ]);

        $this->debug      = new Debug();
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
        return 'ZatoAPI PHP ' . self::VERSION;
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
     * Returns debug information in an object
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
     * @param       $endpoint
     * @param array $postData
     *
     * @return array
     * @throws Exceptions\ApiResponseException
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
     * A ping service which always returns a constant string. Useful for testing clients against Zato clusters.
     */
    public function ping()
    {
        return new Ping($this);
    }

    public function serviceInvoke($params)
    {
        $endpoint = new Invoke($this);
        return $endpoint->execute($params);
    }
}