<?php

namespace Zato\UnitTests\LiveTests;
require dirname(__DIR__) . '/../vendor/autoload.php';

use zato\ZatoClient;
use PHPUnit_Framework_TestCase;

abstract class BasicTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \zato\HttpClient
     */
    protected $client;

    public function setUp()
    {
        $config = array(
            'user' => getenv('ZATO_PUBAPI_USER'),
            'pass' => getenv('ZATO_PUBAPI_PASS'),
            'hostname' => getenv('ZATO_PUBAPI_HOST'),
            'port' => getenv('ZATO_PUBAPI_PORT'));

        $this->client = new ZatoClient($config);
        parent::setUp();
    }



}
