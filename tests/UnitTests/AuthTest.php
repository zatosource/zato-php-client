<?php

namespace Zato\UnitTests;

require dirname(__DIR__).'/../vendor/autoload.php';

use zato\ZatoClient;
use zato\Exceptions\AuthException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit_Framework_TestCase;

class AuthTest extends \PHPUnit_Framework_TestCase
{
    public function testAuthSuccess()
    {
        // Create a mock response
        $body = <<<JSON
{
  "zato_env": {
    "details": "",
    "result": "ZATO_OK",
    "cid": "K06Q3K0JQK8VFJQ7MA8VHGH69GWJ"
  },
  "zato_ping_response": {
    "pong": "zato"
  }
}
JSON;

        $mock = new MockHandler([
            new Response(200,
                [
                    'Server' => 'Zato',
                    'X-Zato-CID' => 'K04ZJTVCXFYFDQTQP7X1EJE1CMY1'
                ], $body)
        ]);

        $handler = HandlerStack::create($mock);

        $client = new ZatoClient(array(), ['handler' => $handler]);
        $client->post('ping', array());
        $this->assertEquals(200, $client->getDebug()->lastResponseCode);
    }

    public function testAuthFail()
    {
        $this->setExpectedException(AuthException::class);

        // Create a mock response
        $body = <<<JSON
{
  "zato_env": {
    "details": "UNAUTHORIZED path_info:[/zato/json/zato.service.invoke], cid:[K04ZJTVCXFYFDQTQP7X1EJE1CMY1], sec-wall code:[0004.0003], description:[]\n",
    "result": "ZATO_ERROR",
    "cid": "K04ZJTVCXFYFDQTQP7X1EJE1CMY1"
  }
}
JSON;

        $mock = new MockHandler([
            new Response(401,
                [
                    'Server' => 'Zato',
                    'X-Zato-CID' => 'K04ZJTVCXFYFDQTQP7X1EJE1CMY1',
                    'WWW-Authenticate' => 'Basic realm="Zato public API"'
                ], $body)
        ]);

        $handler = HandlerStack::create($mock);

        $client = new ZatoClient(array(), ['handler' => $handler]);
        $client->post('ping', array());
    }

}
